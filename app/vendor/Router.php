<?php

namespace app\vendor;

use app\vendor\View;

class Router
{
    protected $routes = [];
    protected $params = [];

    function __construct(){
        $arr = require 'app/config/routes.php';
        // debug($arr);
        foreach ($arr as $key=>$val){
            $this->add($key, $val);
        }
    }

    public function add($route, $params){
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;
    }

    public function match(){
        $url = preg_replace('/\?.*/', '', trim($_SERVER['REQUEST_URI'], '/'));
        foreach($this->routes as $route => $params){
            if (preg_match($route, $url, $matches)){
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    public function run(){
        if ($this->match()){
            $patch = 'app\controllers\\'.ucfirst($this->params['controller']).'Controller';
            if (class_exists($patch)){
                $action = $this->params['action'].'Action';
                if (method_exists($patch, $action)){
                    $controller = new $patch($this->params);
                    $controller->$action();
                }
                else{
                    View::errorCode(404);
                }
            }
            else{
                View::errorCode(404);
            }
        }
        else{
            View::errorCode(404);
        }
    }
}