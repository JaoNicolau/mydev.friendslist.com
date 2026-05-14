<?php

require_once __DIR__ . '/../dao/UserDAO.php';

class UserController {

    private function view($name, $data = []) {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../../public/views/' . $name . '.php';
    }

    public function user($id) {
        $user = (new UserDAO())->findByid($id);

        $this->view('user/profile', ['user'=> $user]);
    }

    public function listAll() {

        (new UserDAO())->getAll();
    }

    public function userUpdate($userId) {

        $username = trim($_POST["username"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $is_admin = isset($_POST["is_admin"]) ? 1 : 0;

        if (empty($email) || empty($username)) {
            throw new Exception("Email e password são obrigatórios");
        }

        $linhasAlteradas = (new UserDAO())->userUpdateDAO($userId, $username, $email, $is_admin);

        if (!$linhasAlteradas) {
            throw new Exception("Erro ao alterar os dados");
        }
    }

    public function userDelete($userId) {
        $linhasAlteradas = (new UserDAO())->userDeleteDAO($userId);

        if (!$linhasAlteradas) {
            throw new Exception("Erro ao alterar os dados");
        }
    }
}