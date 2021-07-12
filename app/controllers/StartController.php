<?php

namespace app\controllers;

use app\vendor\Controller;

class StartController extends Controller
{
    public function indexAction(){
        $this->view->layout = 'start_layout';
        $this->view->render('start page');
    }
}