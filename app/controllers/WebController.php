<?php

class WebController {
    

    private function view($viewName, $data = []) {
        require_once __DIR__ . "/../../public/views/{$viewName}.php";
    }

    public function index() {        
        $this->view('home');
    }

    public function login() {
        $this->view('login');
    }

    public function signup() {
        $this->view('signup');
    }

    public function users() {
        $this->view('users');
    }

    public function dashboard() {
        $this->view('dashboard');
    }
    public function badRequest() {
        $this->view('errors/400');
    }
}