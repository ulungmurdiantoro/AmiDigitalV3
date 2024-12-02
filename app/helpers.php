<?php
  
  if (!function_exists('active_class')) {
    function active_class($routes) {
      foreach ((array) $routes as $route) {
        if (request()->routeIs($route)) {
          return 'active';
        }
      }
      return '';
    }
  }

  if (!function_exists('is_active_route')) {
    function is_active_route($route) {
        return request()->routeIs($route) ? 'active' : '';
    }
}

if (!function_exists('show_class')) {
  function show_class($route) {
      return request()->routeIs($route) ? 'show' : '';
  }
}


