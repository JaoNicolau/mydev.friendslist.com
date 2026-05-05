<?php

class AuthMiddlewareWeb {
  
  // Verifica se um user está logado
  // Com session token
  public static function isLogin() {
    if (isset($_SESSION['token'])) {
      // Se tiver logado true
      return true;
    } else {
      // Se não tiver logado false
      return false;
      
    }
  }

  public static function isAdmin()
  {
    if (isset($_SESSION['token']) && $_SESSION['token']['is_admin']) {
      // Se tiver logado true
      return true;
    } else {
      // Se não tiver logado false
      return false;
    }
  }
}
