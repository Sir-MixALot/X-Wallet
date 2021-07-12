<?php

namespace app\models;

use app\vendor\Model;

class Settings extends Model
 {
    public function Validate( $input, $post ) {
        $rules = [

            'login' => [
                'pattern' => '#^[A-z0-9]{3,15}$#',
                'message' => 'The login is specified incorrectly (only Latin letters and numbers from 3 to 15 characters are allowed)',
            ],

            'password' => [
                'pattern' => '#^[a-z0-9]{6,30}$#',
                'message' => 'The password is incorrect (only Latin letters and numbers from 6 to 30 characters are allowed)',

            ],
        ];
        foreach ( $input as $val ) {
            if ( !isset( $post[$val] ) or !preg_match( $rules[$val]['pattern'], $post[$val] ) ) {
                $this->error = $rules[$val]['message'];
                return false;
            }
        }
        return true;
    }

    public function c_login( $login ) {
        $params = [
            'login' => $_SESSION['account']['login'],
        ];
        if ( !$this->db->column( 'SELECT u_id FROM users WHERE login = :login', $params ) ) {
            $this->error = 'This login is already taken!';
        } else {
            $params = [
                'newLogin' => $login,
                'login' => $_SESSION['account']['login'],
            ];
            $this->db->row( 'UPDATE users SET login=:newLogin WHERE login=:login', $params );
        }
    }

    public function c_pass( $password ) {
        $params = [
            'login' => $_SESSION['account']['login'],
        ];
        if ( ( $this->db->row( 'SELECT password FROM users WHERE login = :login', $params ) === password_hash( $password, PASSWORD_BCRYPT ) ) ) {
            $this->error = 'This is your current password!';
        } else {
            $params = [
                'password' => password_hash( $password, PASSWORD_BCRYPT ),
                'login' => $_SESSION['account']['login'],
            ];
            $this->db->row( 'UPDATE users SET password=:password WHERE login=:login', $params );
        }

    }

    public function addMoney($t_amount) {
        $params = [
            'amount' => $_SESSION['wallet']['total_amount'] + $t_amount,
            'w_id' => $_SESSION['account']['w_id'],
        ];
        $this->db->row( 'UPDATE wallets SET total_amount=:amount WHERE w_id=:w_id', $params );
        $_SESSION['wallet']['total_amount'] = $params['amount'];
    }

}