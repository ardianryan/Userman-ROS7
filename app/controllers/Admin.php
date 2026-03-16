<?php

class Admin extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('auth');
        }
    }

    private function getSettings() {
        return $this->model('SettingsModel')->getAll();
    }

    private function connectApi($router) {
        $api = new RouterosAPI();
        if ($api->connect($router['ip_address'], $router['api_user'], $router['api_pass'])) {
            return $api;
        }
        return null;
    }

    private function getRouter($id) {
        return $this->model('RouterModel')->getRouterById($id);
    }

    private function allRouters() {
        return $this->model('RouterModel')->getAllRouters();
    }

    /** Build base data array for any UM section page */
    private function umBase($routerId, $pageTitle, $umTab, $activePage = 'admin_userman') {
        $router = $this->getRouter($routerId);
        return [
            'title'      => $pageTitle,
            'pageTitle'  => $pageTitle,
            'activePage' => $activePage,
            'umTab'      => $umTab,
            'settings'   => $this->getSettings(),
            'routers'    => $this->allRouters(),
            'router'     => $router,
        ];
    }

    /** Render a UM section view */
    private function umRender($data, $view) {
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view($view, $data);
        $this->view('templates/footer');
    }

    /** Generic API read helper */
    private function apiPrint($api, $path) {
        $api->write($path);
        return $api->read() ?: [];
    }

    // ─── Dashboard ───────────────────────────────────────────
    public function index() {
        $data = [
            'title'      => 'Admin Dashboard',
            'pageTitle'  => 'Dashboard',
            'activePage' => 'admin_dashboard',
            'settings'   => $this->getSettings(),
            'routers'    => $this->allRouters(),
        ];
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('admin/dashboard', $data);
        $this->view('templates/footer');
    }

    // ─── Routers ─────────────────────────────────────────────
    public function routers() {
        $data = [
            'title'      => 'Manage Routers',
            'pageTitle'  => 'Manage Routers',
            'activePage' => 'admin_routers',
            'settings'   => $this->getSettings(),
            'routers'    => $this->allRouters(),
        ];
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('admin/routers', $data);
        $this->view('templates/footer');
    }

    public function add_router() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model('RouterModel')->addRouter($_POST);
        }
        $this->redirect('admin/routers');
    }

    public function delete_router($id) {
        $this->model('RouterModel')->deleteRouter($id);
        $this->redirect('admin/routers');
    }

    public function edit_router() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $routerModel = $this->model('RouterModel');
            $existing    = $routerModel->getRouterById($_POST['id']);
            $updateData  = [
                'id'         => $_POST['id'],
                'name'       => $_POST['name']       ?? $existing['name'],
                'ip_address' => $_POST['ip_address'] ?? $existing['ip_address'],
                'api_user'   => $_POST['api_user']   ?? $existing['api_user'],
                // Keep existing password/secret if blank
                'api_pass'   => !empty($_POST['api_pass'])  ? $_POST['api_pass']  : $existing['api_pass'],
                'secret'     => !empty($_POST['secret'])    ? $_POST['secret']    : $existing['secret'],
            ];
            $routerModel->updateRouter($updateData);
        }
        $this->redirect('admin/routers');
    }

    public function router_monitor($id) {
        $router = $this->getRouter($id);
        $data = [
            'title'      => 'Monitor · ' . ($router['name'] ?? ''),
            'pageTitle'  => 'Router Monitor',
            'activePage' => 'admin_routers',
            'settings'   => $this->getSettings(),
            'routers'    => $this->allRouters(),
            'router'     => $router,
        ];

        // Get static device info only on first load
        $api = $this->connectApi($router);
        if ($api) {
            $res = $api->read() ?: [];  // flush buffer
            $api->write('/system/resource/print');
            $res = $api->read();
            $data['resource']    = $res[0] ?? [];
            $data['interfaces']  = $this->apiPrint($api, '/interface/print');
            $api->disconnect();
        } else {
            $data['api_error'] = 'Cannot connect to router API at ' . htmlspecialchars($router['ip_address']);
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('admin/router_monitor', $data);
        $this->view('templates/footer');
    }

    // JSON endpoint polled by JS every N seconds
    public function router_monitor_json($id) {
        header('Content-Type: application/json');
        // Session already started in init.php; just check auth
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        $router = $this->getRouter($id);
        $api    = $this->connectApi($router);
        if (!$api) {
            echo json_encode(['error' => 'Cannot connect']);
            exit;
        }

        $api->write('/system/resource/print');
        $res = $api->read();
        $res = $res[0] ?? [];

        $api->write('/interface/print');
        $ifaces = $api->read() ?: [];

        $api->write('/ip/firewall/connection/print');
        $conns = $api->read() ?: [];

        $api->disconnect();

        echo json_encode([
            'resource'    => $res,
            'interfaces'  => array_slice($ifaces, 0, 30),
            'connections' => array_slice($conns,  0, 50),
            'ts'          => date('H:i:s'),
        ]);
        exit;
    }

    // ─── UM: Routers ─────────────────────────────────────────
    public function um_routers($routerId) {
        $data = $this->umBase($routerId, 'UM · Routers', 'routers');
        $api  = $this->connectApi($data['router']);
        if ($api) {
            $data['rows'] = $this->apiPrint($api, '/user-manager/router/print');
            $api->disconnect();
        } else {
            $data['api_error'] = 'Could not connect to router API.';
        }
        $this->umRender($data, 'admin/um_routers');
    }

    // ─── UM: Users ───────────────────────────────────────────
    public function um_users($routerId) {
        $data = $this->umBase($routerId, 'UM · Users', 'users');
        $api  = $this->connectApi($data['router']);
        if ($api) {
            $users = $this->apiPrint($api, '/user-manager/user/print');
            $data['profiles'] = $this->apiPrint($api, '/user-manager/profile/print');
            
            // In v7, profile assignment is separate. Fetch and map it to users.
            $userProfiles = $this->apiPrint($api, '/user-manager/user-profile/print');
            $profileMap = [];
            foreach ($userProfiles as $up) {
                // Key by user name. If a user has multiple, this just takes the last one (which is usually fine for a simple view)
                $uName = $up['user'] ?? ($up['customer'] ?? '');
                if (!empty($uName)) {
                    $profileMap[$uName] = $up['profile'] ?? 'default';
                }
            }

            foreach ($users as &$u) {
                $u['profile'] = $profileMap[$u['name']] ?? 'default';
            }
            $data['um_users'] = $users;

            $api->disconnect();
        } else {
            $data['api_error'] = 'Could not connect to router API.';
        }
        $this->umRender($data, 'admin/um_users');
    }

    public function add_userman_user() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $router = $this->getRouter($_POST['router_id']);
            $api = $this->connectApi($router);
            if ($api) {
                // 1. Add User
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                $api->write('/user-manager/user/add', false);
                $api->write('=name='         . $username, false);
                $api->write('=password='     . $password, false);
                $api->write('=shared-users=' . ($_POST['shared_users'] ?? '1'));
                $res = $api->read();

                // 2. Assign Profile (if not default)
                $profile = $_POST['profile'] ?? 'default';
                if ($profile !== 'default' && empty($res['!trap'])) {
                    $api->write('/user-manager/user-profile/add', false);
                    $api->write('=user='    . $username, false);
                    $api->write('=profile=' . $profile);
                    $api->read();
                }
                $api->disconnect();

                if (empty($res['!trap'])) {
                    // Store details in session for the WhatsApp copy feature
                    $_SESSION['flash_new_user'] = [
                        'username' => $username,
                        'password' => $password,
                        'router'   => $router['name'] ?? 'Unknown Router'
                    ];
                }
            }
        }
        $this->redirect('admin/um_users/' . ($_POST['router_id'] ?? ''));
    }

    public function edit_userman_user() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $router = $this->getRouter($_POST['router_id']);
            $api = $this->connectApi($router);
            if ($api) {
                $username = $_POST['username'] ?? '';
                // 1. Update User
                $api->write('/user-manager/user/set', false);
                $api->write('=.id='         . ($_POST['user_id']      ?? ''), false);
                $api->write('=name='        . $username, false);
                if (!empty($_POST['password'])) {
                    $api->write('=password=' . $_POST['password'], false);
                }
                $api->write('=shared-users='. ($_POST['shared_users'] ?? '1'), false);
                $api->write('=disabled='    . ($_POST['disabled']     ?? 'false'));
                $res = $api->read();

                // Profile updates should ideally be managed in UM -> User Profiles since users can have multiple in v7.
                // However, to keep it simple from this modal, we can try to add the new one if selected.
                $profile = $_POST['profile'] ?? 'default';
                if ($profile !== 'default' && empty($res['!trap'])) {
                    // Try to add the profile (might fail if already assigned, which is fine)
                    $api->write('/user-manager/user-profile/add', false);
                    $api->write('=user='    . $username, false);
                    $api->write('=profile=' . $profile);
                    $api->read();
                }

                $api->disconnect();
            }
        }
        $this->redirect('admin/um_users/' . ($_POST['router_id'] ?? ''));
    }

    public function delete_userman_user($routerId, $userId) {
        $router = $this->getRouter($routerId);
        $api = $this->connectApi($router);
        if ($api) {
            $api->write('/user-manager/user/remove', false);
            $api->write('=.id=' . urldecode($userId));
            $api->read();
            $api->disconnect();
        }
        $this->redirect('admin/um_users/' . $routerId);
    }

    // ─── UM: User Groups ─────────────────────────────────────
    public function um_user_groups($routerId) {
        $data = $this->umBase($routerId, 'UM · User Groups', 'user_groups');
        $api  = $this->connectApi($data['router']);
        if ($api) {
            $data['rows'] = $this->apiPrint($api, '/user-manager/user-group/print');
            $api->disconnect();
        } else {
            $data['api_error'] = 'Could not connect to router API.';
        }
        $this->umRender($data, 'admin/um_user_groups');
    }

    public function um_add_group() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $router = $this->getRouter($_POST['router_id']);
            $api = $this->connectApi($router);
            if ($api) {
                $api->write('/user-manager/user-group/add', false);
                $api->write('=name=' . ($_POST['name'] ?? ''));
                $api->read();
                $api->disconnect();
            }
        }
        $this->redirect('admin/um_user_groups/' . ($_POST['router_id'] ?? ''));
    }

    // ─── UM: Sessions ────────────────────────────────────────
    public function um_sessions($routerId) {
        $data = $this->umBase($routerId, 'UM · Sessions', 'sessions');
        $api  = $this->connectApi($data['router']);
        if ($api) {
            $data['rows'] = $this->apiPrint($api, '/user-manager/session/print');
            $api->disconnect();
        } else {
            $data['api_error'] = 'Could not connect to router API.';
        }
        $this->umRender($data, 'admin/um_sessions');
    }

    // ─── UM: Profiles ────────────────────────────────────────
    public function um_profiles($routerId) {
        $data = $this->umBase($routerId, 'UM · Profiles', 'profiles');
        $api  = $this->connectApi($data['router']);
        if ($api) {
            $data['rows'] = $this->apiPrint($api, '/user-manager/profile/print');
            $api->disconnect();
        } else {
            $data['api_error'] = 'Could not connect to router API.';
        }
        $this->umRender($data, 'admin/um_profiles');
    }

    public function add_profile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $routerId = $_POST['router_id'];
            $router   = $this->getRouter($routerId);
            $api      = $this->connectApi($router);
            if ($api) {
                $api->write('/user-manager/profile/add', false);
                $api->write('=name=' . ($_POST['name'] ?? ''));
                $api->read();
                // Fetch new profile id to set rate-limit/timeout
                if (!empty($_POST['rate_limit']) || !empty($_POST['session_timeout'])) {
                    $api->write('/user-manager/profile/print', false);
                    $api->write('?name=' . ($_POST['name'] ?? ''));
                    $ps = $api->read();
                    if (!empty($ps) && isset($ps[0]['.id'])) {
                        if (!empty($_POST['rate_limit'])) {
                            $api->write('/user-manager/profile/set', false);
                            $api->write('=.id=' . $ps[0]['.id'], false);
                            $api->write('=rate-limit=' . $_POST['rate_limit']);
                            $api->read();
                        }
                        if (!empty($_POST['session_timeout'])) {
                            $api->write('/user-manager/profile/set', false);
                            $api->write('=.id=' . $ps[0]['.id'], false);
                            $api->write('=session-timeout=' . $_POST['session_timeout']);
                            $api->read();
                        }
                    }
                }
                $api->disconnect();
            }
        }
        $this->redirect('admin/um_profiles/' . ($_POST['router_id'] ?? ''));
    }

    // ─── UM: User Profiles ───────────────────────────────────
    public function um_user_profiles($routerId) {
        $data = $this->umBase($routerId, 'UM · User Profiles', 'user_profiles');
        $api  = $this->connectApi($data['router']);
        if ($api) {
            $data['rows']     = $this->apiPrint($api, '/user-manager/user-profile/print');
            $data['um_users'] = $this->apiPrint($api, '/user-manager/user/print');
            $data['profiles'] = $this->apiPrint($api, '/user-manager/profile/print');
            $api->disconnect();
        } else {
            $data['api_error'] = 'Could not connect to router API.';
        }
        $this->umRender($data, 'admin/um_user_profiles');
    }

    public function um_assign_user_profile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $router = $this->getRouter($_POST['router_id']);
            $api = $this->connectApi($router);
            if ($api) {
                $api->write('/user-manager/user-profile/add', false);
                $api->write('=user='    . ($_POST['user'] ?? ''), false);
                $api->write('=profile=' . ($_POST['profile']  ?? ''));
                $api->read();
                $api->disconnect();
            }
        }
        $this->redirect('admin/um_user_profiles/' . ($_POST['router_id'] ?? ''));
    }

    // ─── UM: Limitations ─────────────────────────────────────
    public function um_limitations($routerId) {
        $data = $this->umBase($routerId, 'UM · Limitations', 'limitations');
        $api  = $this->connectApi($data['router']);
        if ($api) {
            $data['rows'] = $this->apiPrint($api, '/user-manager/limitation/print');
            $api->disconnect();
        } else {
            $data['api_error'] = 'Could not connect to router API.';
        }
        $this->umRender($data, 'admin/um_limitations');
    }

    public function um_add_limitation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $router = $this->getRouter($_POST['router_id']);
            $api = $this->connectApi($router);
            if ($api) {
                $api->write('/user-manager/limitation/add', false);
                $api->write('=name=' . ($_POST['name'] ?? ''));
                $api->read();
                // Set extra fields
                $api->write('/user-manager/limitation/print', false);
                $api->write('?name=' . ($_POST['name'] ?? ''));
                $ls = $api->read();
                if (!empty($ls) && isset($ls[0]['.id'])) {
                    $sets = [];
                    if (!empty($_POST['rate_limit']))     $sets['rate-limit']      = $_POST['rate_limit'];
                    if (!empty($_POST['download_limit']))  $sets['download-limit']  = $_POST['download_limit'];
                    if (!empty($_POST['upload_limit']))    $sets['upload-limit']    = $_POST['upload_limit'];
                    if (!empty($_POST['session_timeout'])) $sets['session-timeout'] = $_POST['session_timeout'];
                    if (!empty($sets)) {
                        $api->write('/user-manager/limitation/set', false);
                        $api->write('=.id=' . $ls[0]['.id'], false);
                        foreach ($sets as $k => $v) {
                            $api->write('=' . $k . '=' . $v, false);
                        }
                        $api->write('');
                        $api->read();
                    }
                }
                $api->disconnect();
            }
        }
        $this->redirect('admin/um_limitations/' . ($_POST['router_id'] ?? ''));
    }

    // ─── UM: Profile Limitations ─────────────────────────────
    public function um_profile_limitations($routerId) {
        $data = $this->umBase($routerId, 'UM · Profile Limitations', 'profile_limitations');
        $api  = $this->connectApi($data['router']);
        if ($api) {
            $data['rows']         = $this->apiPrint($api, '/user-manager/profile-limitation/print');
            $data['profiles']     = $this->apiPrint($api, '/user-manager/profile/print');
            $data['limitations']  = $this->apiPrint($api, '/user-manager/limitation/print');
            $api->disconnect();
        } else {
            $data['api_error'] = 'Could not connect to router API.';
        }
        $this->umRender($data, 'admin/um_profile_limitations');
    }

    public function um_add_profile_limitation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $router = $this->getRouter($_POST['router_id']);
            $api = $this->connectApi($router);
            if ($api) {
                $api->write('/user-manager/profile-limitation/add', false);
                $api->write('=profile='    . ($_POST['profile']    ?? ''), false);
                $api->write('=limitation=' . ($_POST['limitation'] ?? ''), false);
                $api->write('=from-time='  . ($_POST['from_time']  ?? '00:00:00'), false);
                $api->write('=till-time='  . ($_POST['till_time']  ?? '23:59:59'));
                $api->read();
                $api->disconnect();
            }
        }
        $this->redirect('admin/um_profile_limitations/' . ($_POST['router_id'] ?? ''));
    }

    // ─── UM: Attributes ──────────────────────────────────────
    public function um_attributes($routerId) {
        $data = $this->umBase($routerId, 'UM · Attributes', 'attributes');
        $api  = $this->connectApi($data['router']);
        if ($api) {
            $data['rows'] = $this->apiPrint($api, '/user-manager/attribute/print');
            $api->disconnect();
        } else {
            $data['api_error'] = 'Could not connect to router API.';
        }
        $this->umRender($data, 'admin/um_attributes');
    }

    // ─── UM: Payment ─────────────────────────────────────────
    public function um_payment($routerId) {
        $data = $this->umBase($routerId, 'UM · Payment', 'payment');
        $api  = $this->connectApi($data['router']);
        if ($api) {
            $data['rows'] = $this->apiPrint($api, '/user-manager/payment/print');
            $api->disconnect();
        } else {
            $data['api_error'] = 'Could not connect to router API.';
        }
        $this->umRender($data, 'admin/um_payment');
    }

    // ─── UM: Generic Delete ───────────────────────────────────
    public function um_delete($routerId, $apiPath, $itemId, $returnTab) {
        $router = $this->getRouter($routerId);
        $api = $this->connectApi($router);
        if ($api) {
            $api->write('/user-manager/' . $apiPath . '/remove', false);
            $api->write('=.id=' . urldecode($itemId));
            $api->read();
            $api->disconnect();
        }
        $this->redirect('admin/um_' . $returnTab . '/' . $routerId);
    }


    // ─── Admin Profile ───────────────────────────────────────
    public function profile() {
        $data = [
            'title'      => 'My Profile',
            'pageTitle'  => 'My Profile',
            'activePage' => 'admin_profile',
            'settings'   => $this->getSettings(),
            'routers'    => $this->allRouters(),
        ];
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('admin/profile', $data);
        $this->view('templates/footer');
    }

    public function update_profile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/profile');
        }
        $um      = $this->model('UserModel');
        $userId  = $_SESSION['user_id'] ?? 0;
        $user    = $um->getUserById($userId);

        $data = [
            'title'      => 'My Profile',
            'pageTitle'  => 'My Profile',
            'activePage' => 'admin_profile',
            'settings'   => $this->getSettings(),
            'routers'    => $this->allRouters(),
        ];

        if (!$user || !password_verify($_POST['current_password'] ?? '', $user['password'])) {
            $data['error'] = 'Current password is incorrect.';
        } elseif (strlen($_POST['new_password'] ?? '') < 6) {
            $data['error'] = 'New password must be at least 6 characters.';
        } elseif (($_POST['new_password'] ?? '') !== ($_POST['confirm_password'] ?? '')) {
            $data['error'] = 'Passwords do not match.';
        } else {
            $um->updatePassword($userId, $_POST['new_password']);
            $data['success'] = 'Password updated successfully!';
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('admin/profile', $data);
        $this->view('templates/footer');
    }

    // ─── Admin User Management ───────────────────────────────
    public function admin_users() {
        $data = [
            'title'       => 'Admin Users',
            'pageTitle'   => 'Admin Users',
            'activePage'  => 'admin_users',
            'settings'    => $this->getSettings(),
            'routers'     => $this->allRouters(),
            'admin_users' => $this->model('UserModel')->getAllUsers(),
        ];
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('admin/admin_users', $data);
        $this->view('templates/footer');
    }

    public function create_admin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $um = $this->model('UserModel');
            if (strlen($_POST['password'] ?? '') < 6) {
                $_SESSION['flash_error'] = 'Password must be at least 6 characters.';
            } elseif ($um->usernameExists($_POST['username'])) {
                $_SESSION['flash_error'] = 'Username already exists.';
            } else {
                $um->createUser([
                    'username' => $_POST['username'] ?? '',
                    'password' => $_POST['password'] ?? '',
                    'role'     => $_POST['role']     ?? 'admin',
                ]);
                $_SESSION['flash_success'] = 'Admin user created.';
            }
        }
        $this->redirect('admin/admin_users');
    }

    public function edit_admin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $um = $this->model('UserModel');
            $id = $_POST['id'] ?? 0;
            if ($um->usernameExists($_POST['username'] ?? '', $id)) {
                $_SESSION['flash_error'] = 'Username already used by another admin.';
            } else {
                $um->updateUser([
                    'id'       => $id,
                    'username' => $_POST['username'] ?? '',
                    'role'     => $_POST['role']     ?? 'admin',
                ]);
                // Update session name if editing self
                if ($id == ($_SESSION['user_id'] ?? -1)) {
                    $_SESSION['user_name'] = $_POST['username'] ?? $_SESSION['user_name'];
                }
                $_SESSION['flash_success'] = 'Admin updated.';
            }
        }
        $this->redirect('admin/admin_users');
    }

    public function admin_reset_password() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            if (($_POST['new_password'] ?? '') !== ($_POST['confirm_password'] ?? '')) {
                $_SESSION['flash_error'] = 'Passwords do not match.';
            } elseif (strlen($_POST['new_password'] ?? '') < 6) {
                $_SESSION['flash_error'] = 'Password must be at least 6 characters.';
            } else {
                $this->model('UserModel')->updatePassword($id, $_POST['new_password']);
                $_SESSION['flash_success'] = 'Password reset successfully.';
            }
        }
        $this->redirect('admin/admin_users');
    }

    public function delete_admin($id) {
        if ($id == ($_SESSION['user_id'] ?? -1)) {
            $_SESSION['flash_error'] = 'You cannot delete your own account.';
        } else {
            $this->model('UserModel')->deleteUser($id);
            $_SESSION['flash_success'] = 'Admin deleted.';
        }
        $this->redirect('admin/admin_users');
    }

    public function regenerate_ref_code() {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // no ambiguous chars
        $code  = '';
        for ($i = 0; $i < 8; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        $formatted = substr($code, 0, 4) . '-' . substr($code, 4);
        $this->model('SettingsModel')->set('ref_code', $formatted);
        $this->redirect('admin/settings');
    }

    // ─── Settings ─────────────────────────────────────────────
    public function settings() {
        $settingsModel = $this->model('SettingsModel');
        $data = [
            'title'      => 'App Settings',
            'pageTitle'  => 'App Settings',
            'activePage' => 'admin_settings',
            'settings'   => $settingsModel->getAll(),
            'routers'    => $this->allRouters(),
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingsModel->set('app_name',          $_POST['app_name']          ?? 'MikroTik UserMan');
            $settingsModel->set('app_tagline',       $_POST['app_tagline']       ?? '');
            $settingsModel->set('portal_title',      $_POST['portal_title']      ?? 'User Portal');
            $settingsModel->set('portal_description',$_POST['portal_description']?? '');

            if (!empty($_FILES['app_logo']['name'])) {
                $ext = pathinfo($_FILES['app_logo']['name'], PATHINFO_EXTENSION);
                if (in_array(strtolower($ext), ['png','jpg','jpeg','svg','webp','gif'])) {
                    $newName = 'assets/img/logo_' . time() . '.' . $ext;
                    $dest = __DIR__ . '/../../public/' . $newName;
                    if (move_uploaded_file($_FILES['app_logo']['tmp_name'], $dest)) {
                        $settingsModel->set('app_logo', $newName);
                    }
                }
            }
            $data['success']  = 'Settings saved successfully!';
            $data['settings'] = $settingsModel->getAll();
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('admin/settings', $data);
        $this->view('templates/footer');
    }
}
