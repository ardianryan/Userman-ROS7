<?php

class User extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
            $this->redirect('auth');
        }
    }

    public function index() {
        $data['title'] = 'User Dashboard';
        $data['pageTitle'] = 'Dashboard';
        $data['activePage'] = 'user_dashboard';

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('user/dashboard', $data);
        $this->view('templates/footer');
    }

    public function change_password() {
        $data['title'] = 'Change Dot1x Password';
        $data['pageTitle'] = 'Change Password';
        $data['activePage'] = 'user_change_password';
        $routerModel = $this->model('RouterModel');
        $data['routers'] = $routerModel->getAllRouters();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $router_id = $_POST['router_id'];
            $new_password = $_POST['new_password'];
            $username = $_SESSION['user_name'];

            $router = $routerModel->getRouterById($router_id);

            $api = new RouterosAPI();
            if ($api->connect($router['ip_address'], $router['api_user'], $router['api_pass'])) {
                $api->write('/user-manager/user/print', false);
                $api->write('?name=' . $username);
                $users = $api->read();

                if (!empty($users)) {
                    $uid = $users[0]['.id'];
                    $api->write('/user-manager/user/set', false);
                    $api->write('=.id=' . $uid, false);
                    $api->write('=password=' . $new_password);
                    $api->read();
                    $data['success'] = "Password for dot1x updated successfully on router: " . htmlspecialchars($router['name']);
                } else {
                    $data['error'] = "User '$username' not found in User Manager of router: " . htmlspecialchars($router['name']);
                }
                $api->disconnect();
            } else {
                $data['error'] = "Failed to connect to router API. Please check router connectivity.";
            }
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('user/change_password', $data);
        $this->view('templates/footer');
    }
}
