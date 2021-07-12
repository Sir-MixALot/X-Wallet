<?php

namespace app\controllers;

use app\vendor\Controller;

class MainController extends Controller
 {
    public function mainAction() {
        $this->view->layout = 'main_layout';
        $this->view->render( 'Main page' );
    }

    public function noteAction()
    {
        $this->model->makeNote($_POST);
        $this->view->redirect( '/main' );
    }

    public function filterDashboardAction()
    {
        $this->view->render( 'Main page' );
    }
}