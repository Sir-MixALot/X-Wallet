<?php

namespace app\controllers;

use app\vendor\Controller;

class SettingsController extends Controller
{
    public function settingsAction(){
        $this->view->layout = 'settings_layout';
        $this->view->render('Settings page');
        
    }

    public function change_loginAction(){
        if ( !empty( $_POST ) ) {
            if ( !$this->model->Validate( ['login'], $_POST ) ) {
                $this->view->message( 'error', $this->model->error );
            } 
            $this->model->c_login( $_POST['login'] );
            unset($_SESSION['account']);
            unset( $_SESSION['wallet'] );
            $this->view->message( 'redirect', '/account/login' );
        }
    }

    public function change_passAction(){
        if ( !empty( $_POST ) ) {
            if ( !$this->model->Validate( ['password'], $_POST ) ) {
                $this->view->message( 'error', $this->model->error );
            } 
            $this->model->c_pass( $_POST['password'] );
            unset($_SESSION['account']);
            unset( $_SESSION['wallet'] );
            $this->view->message( 'redirect', '/account/login' );
        }
    }

    public function total_amountAction(){
        $this->model->addMoney( $_POST['total_amount'] );
        $this->view->message( 'redirect', '/settings' );
    }
}