<?php
require_once __DIR__ . '/../dao/UserDao.php';

class UserController {

    private function view($name){
        require __DIR__ . '/../../public/views/' . $name . '.php';
    }
    
    public function getAllUsers() {
        $userDAO = new UserDAO();
        $users = $userDAO->getAllUsers();

        $numUsers = $userDAO->countUsers();

        $result = [
            'numUsers' => $numUsers,
            'users' => $users,
            'numUserDoPorto' => 100000
        ];

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}