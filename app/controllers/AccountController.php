<?php

namespace app\controllers;

use app\vendor\Controller;

class AccountController extends Controller
 {
    public function loginAction() {
        $this->view->layout = 'entry';
        if ( !empty( $_POST ) ) {
            if ( !$this->model->loginValidate( ['login', 'password'], $_POST ) ) {
                $this->view->message( 'error', $this->model->error );
            } elseif ( !$this->model->checkData( $_POST['login'], $_POST['password'] ) ) {
                $this->view->message( 'error', 'Wrong login or password' );
            }
            $this->model->login( $_POST['login'] );
            $this->view->message( 'redirect', '/main' );
        }

        $this->view->render( 'login page' );
    }

    public function signupAction() {
        $this->view->layout = 'entry';
        if ( !empty( $_POST ) ) {
            $errors = $this->model->registerMultiple(['login', 'password', 'conf_password', 'email'], $_POST);
            
            if ($errors) {
                $messages = array_map(function($error) {
                    return $error['message'];
                }, $errors);
                $this->view->messageMultiple('error-multiple', $messages);
            }

            $this->view->message( 'redirect', '/account/login' );
        }

        $this->view->render( 'signup page' );
    }

    public function recoveryAction() {
        $this->view->layout = 'entry';
        if ( !empty( $_POST ) ) {
            if ( !$this->model->recValidate( ['login', 'email'], $_POST ) ) {
                $this->view->message( 'error', $this->model->error );
            }
            // } elseif ( !$this->model->checkRecovery( $_POST['login'],  $_POST['email']) ) {
            //     $this->view->message( 'error', 'Wrong login or password' );
            // }
            $this->model->recovery( $_POST['login'],  $_POST['email'] );
            $this->view->message( 'redirect', '/account/new_pass' );
        }
        
        $this->view->render( 'recovery page' );
    }

    public function new_passAction() {
        $this->view->layout = 'entry';
        if ( !empty( $_POST ) ) {
            if ( !$this->model->passValidate( ['password', 'conf_password'], $_POST ) ) {
                $this->view->message( 'error', $this->model->error );
            }
            $this->model->recovery_pass( $_POST['password'] );
            unset( $_SESSION['recData'] );
            $this->view->message( 'redirect', '/account/login' );
        }
        $this->view->render( 'new_pass page' );
    }

    public function logoutAction() {
        unset( $_SESSION['account'] );
        unset( $_SESSION['wallet'] );
        $this->view->redirect( '' );
    }

    public function deleteAction() {
        $this->model->delete( $_SESSION['account']['u_id'], $_SESSION['account']['w_id'] );
        unset( $_SESSION['account'] );
        unset( $_SESSION['wallet'] );
        $this->view->redirect( '' );
    }
}