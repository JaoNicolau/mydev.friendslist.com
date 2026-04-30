<?php

class WebController {

    private function view($name){
        require __DIR__ . '/../../public/views/' . $name . '.php';
    }
    public function index() {
        $this->view('home');
    }

    public function login() {
        $this->view('login');
    }

    public function about() {
        $this->view('about');
    }

    public function signup() {
        $this->view('signup');
    }

    // Errors Pages
    public function badRequest() {
        $this->view('errors/400');
    }
}