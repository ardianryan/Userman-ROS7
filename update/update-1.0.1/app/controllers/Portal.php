<?php

class Portal extends Controller {

    private function getSettings() {
        $settingsModel = $this->model('SettingsModel');
        return $settingsModel->getAll();
    }

    public function index() {
        if (isset($_SESSION['portal_user'])) {
            $this->redirect('portal/dashboard');
        }
        $data['title'] = 'User Portal';
        $data['settings'] = $this->getSettings();
        $data['routers'] = $this->model('RouterModel')->getAllRouters();
        $this->view('templates/header', $data);
        $this->view('portal/login', $data);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('portal');
        }
        $this->csrf_check();
        $_POST = Security::sanitize($_POST);
        
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $router_id = $_POST['router_id'] ?? '';

        $data['title'] = 'User Portal';
        $data['settings'] = $this->getSettings();
        $data['routers'] = $this->model('RouterModel')->getAllRouters();

        if (empty($username) || empty($password) || empty($router_id)) {
            $data['error'] = 'All fields are required.';
            $this->view('templates/header', $data);
            $this->view('portal/login', $data);
            return;
        }

        $router = $this->model('RouterModel')->getRouterById($router_id);
        if (!$router) {
            $data['error'] = 'Selected router not found.';
            $this->view('templates/header', $data);
            $this->view('portal/login', $data);
            return;
        }

        $api = new RouterosAPI();
        if (!$api->connect($router['ip_address'], $router['api_user'], $router['api_pass'])) {
            $data['error'] = 'Cannot connect to router. Please contact admin.';
            $this->view('templates/header', $data);
            $this->view('portal/login', $data);
            return;
        }

        // Find user in UM
        $api->write('/user-manager/user/print', false);
        $api->write('?name=' . $username);
        $users = $api->read();
        $api->disconnect();

        if (empty($users)) {
            $data['error'] = 'Username not found in User Manager.';
            $this->view('templates/header', $data);
            $this->view('portal/login', $data);
            return;
        }

        $umUser = $users[0];
        // Verify password against UM (plain text in UM)
        if (($umUser['password'] ?? '') !== $password) {
            $data['error'] = 'Invalid password.';
            $this->view('templates/header', $data);
            $this->view('portal/login', $data);
            return;
        }

        // Auth success
        $_SESSION['portal_user'] = $username;
        $_SESSION['portal_router_id'] = $router_id;
        $_SESSION['portal_router_name'] = $router['name'];
        $_SESSION['portal_um_id'] = $umUser['.id'] ?? '';
        $this->redirect('portal/dashboard');
    }

    public function dashboard() {
        if (!isset($_SESSION['portal_user'])) {
            $this->redirect('portal');
        }
        $data['title'] = 'My Dashboard';
        $data['pageTitle'] = 'My Dashboard';
        $data['activePage'] = 'portal_dashboard';
        $data['settings'] = $this->getSettings();
        $this->view('templates/header', $data);
        $this->view('portal/sidebar', $data);
        $this->view('portal/dashboard', $data);
        $this->view('templates/portal_footer');
    }

    public function change_password() {
        if (!isset($_SESSION['portal_user'])) {
            $this->redirect('portal');
        }
        $data['title'] = 'Change Password';
        $data['pageTitle'] = 'Change Password';
        $data['activePage'] = 'portal_change_password';
        $data['settings'] = $this->getSettings();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->csrf_check();
            $_POST = Security::sanitize($_POST);
            $currentPass = $_POST['current_password'] ?? '';
            $newPass = $_POST['new_password'] ?? '';
            $confirmPass = $_POST['confirm_password'] ?? '';

            if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
                $data['error'] = 'All fields are required.';
            } elseif ($newPass !== $confirmPass) {
                $data['error'] = 'New passwords do not match.';
            } elseif (strlen($newPass) < 4) {
                $data['error'] = 'Password must be at least 4 characters.';
            } else {
                $router = $this->model('RouterModel')->getRouterById($_SESSION['portal_router_id']);
                $api = new RouterosAPI();
                if ($api->connect($router['ip_address'], $router['api_user'], $router['api_pass'])) {
                    // Verify current password
                    $api->write('/user-manager/user/print', false);
                    $api->write('?name=' . $_SESSION['portal_user']);
                    $users = $api->read();

                    if (!empty($users) && ($users[0]['password'] ?? '') === $currentPass) {
                        $api->write('/user-manager/user/set', false);
                        $api->write('=.id=' . $users[0]['.id'], false);
                        $api->write('=password=' . $newPass);
                        $api->read();
                        $api->disconnect();
                        $data['success'] = 'Password updated successfully!';
                    } else {
                        $api->disconnect();
                        $data['error'] = 'Current password is incorrect.';
                    }
                } else {
                    $data['error'] = 'Cannot connect to router.';
                }
            }
        }

        $this->view('templates/header', $data);
        $this->view('portal/sidebar', $data);
        $this->view('portal/change_password', $data);
        $this->view('templates/portal_footer');
    }

    public function logout() {
        unset($_SESSION['portal_user'], $_SESSION['portal_router_id'],
              $_SESSION['portal_router_name'], $_SESSION['portal_um_id']);
        $this->redirect('portal');
    }

    // ─── Self-Registration ────────────────────────────────────
    public function register() {
        if (isset($_SESSION['portal_user'])) {
            $this->redirect('portal/dashboard');
        }
        $data = [
            'title'    => 'Register · ' . ($this->getSettings()['app_name'] ?? 'UserMan'),
            'settings' => $this->getSettings(),
            'routers'  => $this->model('RouterModel')->getAllRouters(),
        ];
        $this->view('portal/register', $data);
    }

    public function do_register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('portal/register');
        }
        $this->csrf_check();
        $_POST = Security::sanitize($_POST);

        $settings   = $this->getSettings();
        $routers    = $this->model('RouterModel')->getAllRouters();
        $data = [
            'title'    => 'Register',
            'settings' => $settings,
            'routers'  => $routers,
        ];

        $username   = trim($_POST['username']         ?? '');
        $password   = $_POST['password']              ?? '';
        $confirmPw  = $_POST['confirm_password']      ?? '';
        $inputCode  = strtoupper(trim($_POST['ref_code'] ?? ''));
        $routerId   = $_POST['router_id']             ?? '';
        $validCode  = strtoupper(trim($settings['ref_code'] ?? ''));

        // ── Validate inputs ──────────────────────────────────
        if (empty($username) || empty($password) || empty($inputCode) || empty($routerId)) {
            $data['error'] = 'All fields are required.';
            $this->view('portal/register', $data); return;
        }
        if (strlen($password) < 4) {
            $data['error'] = 'Password must be at least 4 characters.';
            $this->view('portal/register', $data); return;
        }
        if ($password !== $confirmPw) {
            $data['error'] = 'Passwords do not match.';
            $this->view('portal/register', $data); return;
        }
        if (empty($validCode) || $inputCode !== $validCode) {
            $data['error'] = 'Invalid reference code. Please contact your administrator.';
            $this->view('portal/register', $data); return;
        }

        // ── Connect to router API ────────────────────────────
        $router = $this->model('RouterModel')->getRouterById($routerId);
        if (!$router) {
            $data['error'] = 'Selected router not found.';
            $this->view('portal/register', $data); return;
        }
        $api = new RouterosAPI();
        if (!$api->connect($router['ip_address'], $router['api_user'], $router['api_pass'])) {
            $data['error'] = 'Cannot connect to router. Please try again later.';
            $this->view('portal/register', $data); return;
        }

        // ── Check username not already taken ─────────────────
        $api->write('/user-manager/user/print', false);
        $api->write('?name=' . $username);
        $existing = $api->read();
        if (!empty($existing)) {
            $api->disconnect();
            $data['error'] = 'Username "' . htmlspecialchars($username) . '" is already taken. Please choose another.';
            $this->view('portal/register', $data); return;
        }

        // ── Create user in User Manager ──────────────────────
        $api->write('/user-manager/user/add', false);
        $api->write('=name='     . $username, false);
        $api->write('=password=' . $password);
        $api->read();

        // ── Auto-assign to 'guest' user-profile ─────────────
        $api->write('/user-manager/user-profile/add', false);
        $api->write('=user='    . $username, false);
        $api->write('=profile=guest');
        $profRes = $api->read();

        $api->disconnect();

        if (isset($addRes['!trap']) || isset($profRes['!trap'])) {
            $data['error'] = 'Registration succeeded, but profile assignment failed or part of it was rejected by router.';
            $this->view('portal/register', $data); return;
        }

        $data['success'] = 'Account created successfully! You can now log in with your credentials.';
        $this->view('portal/register', $data);
    }
}
