<?php

namespace app\models;

use app\vendor\Model;

class Account extends Model
 {

    private function prepareAccountsData($inputFields, $post)
    {
        $accounts = [];

        foreach (array_keys($post) as $accountDataKey) {
            foreach ($inputFields as $requiredInputField) {
                if (strpos($accountDataKey, $requiredInputField) === 0) {
                    $id = str_replace("$requiredInputField-", '', $accountDataKey);
                    $accounts[$id][$requiredInputField] = $post[$accountDataKey];
                }
            }
        }

        return $accounts;
    }

    public function registerMultiple($inputFields, $post)
    {
        $accounts = $this->prepareAccountsData($inputFields, $post);
        $errors = $this->signupValidateMultiple($inputFields, $accounts);

        if ($errors) {
            return $errors;
        }

        $accountType = count($accounts) === 1 ? 1 : 2;
        $this->db->query( "INSERT INTO wallets VALUES (NULL, $accountType, NULL, NULL)" );
        $walletId = $this->db->lastInsertId();

        foreach($accounts as $accountData) {
            $this->registration($accountData, $walletId);
        }

        return [];
    }

    public function signupValidateMultiple($inputFields, $accounts)
    {
        $errors = [];
        $accountsCount = count($accounts);
        $uniqueEmailsCount = count(array_unique(array_column($accounts, 'email')));
        $uniqueLoginCount = count(array_unique(array_column($accounts, 'login')));

        if ($accountsCount !== $uniqueEmailsCount) {
            $errors[] = [
                'message' => 'There are some accounts with same emails.'
            ];
        }

        if ($accountsCount !== $uniqueLoginCount) {
            $errors[] = [
                'message' => 'There are some accounts with same logins.'
            ];
        }

        foreach($accounts as $accountData) {
            if (!$this->signupValidate($inputFields, $accountData)) {
                $errors[] = [
                    'message' => $this->error . sprintf(' For account with login %s', $accountData['login']),
                ];
            } else {
                if ($this->checkEmailExists($accountData['email'])) {
                    $errors[] = [
                        'message' => 'This email address is already taken!' . sprintf(' For account with login %s', $accountData['login']),
                    ];
                }

                if (!$this->checkLoginExists($accountData['login'])) {
                    $errors[] = [
                        'message' => $this->error . sprintf(' For account with login %s', $accountData['login']),
                    ];
                }
            }
        } 

        return $errors;
    }

    public function signupValidate( $input, $post ) {
        $rules = [

            'login' => [
                'pattern' => '#^[A-z0-9]{3,15}$#',
                'message' => 'The login is specified incorrectly (only Latin letters and numbers from 3 to 15 characters are allowed)',
            ],

            'password' => [
                'pattern' => '#^[a-z0-9]{6,30}$#',
                'message' => 'The password is incorrect (only Latin letters and numbers from 6 to 30 characters are allowed)',

            ],

            'conf_password' => [
                'pattern' => '#^[a-z0-9]{6,30}$#',
                'message' => 'The password is incorrect (only Latin letters and numbers from 6 to 30 characters are allowed)',

            ],

            'email' => [
                'pattern' => '#^([a-z0-9_.-]{1,20}+)@([a-z0-9_.-]+)\.([a-z\.]{2,10})$#',
                'message' => 'E-mail address is incorrect',
            ],

        ];
        foreach ( $input as $val ) {
            if ( !isset( $post[$val] ) or !preg_match( $rules[$val]['pattern'], $post[$val] ) ) {
                $this->error = $rules[$val]['message'];
                return false;
            }
        }
        if ( $post['password'] != $post['conf_password'] ) {
            $this->error = 'Passwords does not match!';
            return false;
        }
        return true;
    }

    public function loginValidate( $input, $post ) {
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

    public function recValidate( $input, $post ) {
        $rules = [

            'login' => [
                'pattern' => '#^[A-z0-9]{3,15}$#',
                'message' => 'The login is specified incorrectly (only Latin letters and numbers from 3 to 15 characters are allowed)',
            ],

            'email' => [
                'pattern' => '#^([a-z0-9_.-]{1,20}+)@([a-z0-9_.-]+)\.([a-z\.]{2,10})$#',
                'message' => 'E-mail address is incorrect',
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

    public function passValidate( $input, $post ) {
        $rules = [

            'password' => [
                'pattern' => '#^[a-z0-9]{6,30}$#',
                'message' => 'The password is incorrect (only Latin letters and numbers from 6 to 30 characters are allowed)',

            ],

            'conf_password' => [
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
        if ( $post['password'] != $post['conf_password'] ) {
            $this->error = 'Passwords are not match!';
            return false;
        }
        return true;
    }

    public function checkEmailExists( $email ) {
        $params = [
            'email' => $email,
        ];
        $this->error = 'This email is already taken!';
        return $this->db->column( 'SELECT u_id FROM users WHERE email = :email', $params );
    }

    public function checkLoginExists( $login ) {
        $params = [
            'login' => $login,
        ];
        if ( $this->db->column( 'SELECT u_id FROM users WHERE login = :login', $params ) ) {
            $this->error = 'This login is already taken!';
            return false;
        }
        return true;
    }

    public function checkData( $login, $password ) {
        $params = [
            'login' => $login,
        ];
        $hash = $this->db->column( 'SELECT password FROM users WHERE login = :login', $params );
        if ( !$hash or !password_verify( $password, $hash ) ) {
            return false;
        }
        return true;
    }

    // public function checkRecovery( $login, $email ) {
    //     $params = [
    //         'login' => $login,
    //     ];
    //     $hash = $this->db->column( 'SELECT * FROM users WHERE login = :login', $params );
    //     if ( !$hash or !password_verify( $email, $hash ) ){
    //         return false;
    //     }
    //     return true;
    // }

    public function login( $login ) {
        $params = [
            'login' => $login,
        ];
        $userData = $this->db->row( 'SELECT * FROM users WHERE login = :login', $params );
        $_SESSION['account'] = $userData[0];
        $walletData = $this->db->row( 'SELECT * FROM wallets WHERE w_id = '.$_SESSION['account']['w_id'].'' );
        $_SESSION['wallet'] = $walletData[0];
        // $wastedData = $this->db->row( 'SELECT * FROM wasted WHERE w_id = '.$_SESSION['account']['w_id'].'' );
        // $_SESSION['wasted'] = $wastedData[0];
    }

    public function registration( $post, $walletId) {
        $params = [
            'login' => $post['login'],
            'password' => password_hash( $post['password'], PASSWORD_BCRYPT ),
            'email' => $post['email'],
        ];
        $this->db->query( 'INSERT INTO users VALUES (NULL, :login, :password, :email, '.$walletId.')', $params );
    }

    public function recovery( $login, $email ) {
        $_SESSION['recData']['login'] = $login;
        $_SESSION['recData']['email'] = $email;
    }

    public function recovery_pass( $password ) {
        $params = [
            'password' => password_hash( $password, PASSWORD_BCRYPT ),
            'login' => $_SESSION['recData']['login'],
        ];
        $this->db->column( 'UPDATE users SET password=:password WHERE login=:login', $params );
    }


    public function delete( $u_id, $w_id ) {
        if($_SESSION['wallet']['acc_id'] == 1){
            $this->db->row( 'DELETE FROM users WHERE u_id = '.$u_id.'', );
            $wst_count;
                $wst_count = $this->db->query( 'SELECT COUNT(waste_id) FROM wasted WHERE w_id = '.$w_id.'' );
                if($wst_count > 0){
                    $this->db->row( 'DELETE FROM wasted WHERE w_id = '.$w_id.'', );
                    $this->db->row( 'DELETE FROM wallets WHERE w_id = '.$w_id.'', );
                }
        }else{
            $count;
            $count = $this->db->query( 'SELECT COUNT(login) FROM users WHERE w_id = '.$w_id.'' );
            if($count > 1){
                
                $this->db->row( 'DELETE FROM users WHERE u_id = '.$u_id.'', );
            }else{
                $this->db->row( 'DELETE FROM users WHERE u_id = '.$u_id.'', );
                $w_count;
                $w_count = $this->db->query( 'SELECT COUNT(waste_id) FROM wasted WHERE w_id = '.$w_id.'' );
                if($w_count > 0){
                    $this->db->row( 'DELETE FROM wasted WHERE w_id = '.$w_id.'', );
                    $this->db->row( 'DELETE FROM wallets WHERE w_id = '.$w_id.'', );
                }
            }
        }
    }

}