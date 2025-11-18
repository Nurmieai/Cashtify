<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('set_active')) {
    function set_active($routes)
    {
        foreach ((array) $routes as $route) {
            if (Route::is($route)) {
                return 'active';
            }
        }
        return '';
    }
}

if (!function_exists('set_menu_open')) {
    function set_menu_open($routes)
    {
        foreach ((array) $routes as $route) {
            if (Route::is($route)) {
                return 'menu-open';
            }
        }
        return '';
    }
}
