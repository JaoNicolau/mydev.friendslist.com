<?php
require_once __DIR__ . '/../dao/UserDAO.php';

class UserController {
  
  private function view($name, $data = [])
  {
    extract($data, EXTR_SKIP);

    require __DIR__ . '/../../public/views/' . $name . '.php';
  }

  public function user($id) {
    $user = (new UserDAO())->findById($id);

    $this->view('user/profile', ['user' => $user]);
  }
}