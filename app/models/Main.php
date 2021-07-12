<?php

namespace app\models;

use app\vendor\Model;

class Main extends Model
{
    public function getDashboardData()
    {
        $day = 3600 * 24;
        $currentTimestamp = time();
        $currentDate = date("Y-m-d");
        $w_id = (int)$_SESSION['wallet']['w_id'];
        $sql = "SELECT spending.type as type, amount, date FROM wasted INNER JOIN spending ON spending.s_id = wasted.s_id WHERE w_id = \"$w_id\"";
        $filter = $_GET['filter'] ?? 'all';

        switch ($filter) {
            case 'today':
                $whereClause = "and date = \"$currentDate\"";
                break;
            case 'week':
                $fromDate = date("Y-m-d", $currentTimestamp - ($day * 7));
                $whereClause = "and date >= \"$fromDate\" AND date <= \"$currentDate\"";
                break;
            case 'month':
                $fromDate = date("Y-m-d", $currentTimestamp - ($day * 30));
                $whereClause = "and date >= \"$fromDate\" AND date <= \"$currentDate\"";
                break;
            default:
            $whereClause = '';
        }
        return $this->db->query($sql . ' ' . $whereClause);
    }

    public function makeNote ($post)
    {
        $currentDate = date("Y-m-d");
        $params = [
            'wallet' => (int)$_SESSION['wallet']['w_id'],
            'amount' => $post['amount'] ?? null,
            'type' => (int)($post['type'] ?? null),
            'date' => $currentDate
        ];

        if ($params['type'] === 8) {
            $this->db->query(
                'UPDATE wallets SET accumulated=:accumulated WHERE w_id=:w_id', 
                [
                    'w_id' => (int)$_SESSION['wallet']['w_id'],
                    'accumulated' => $_SESSION['wallet']['accumulated'] + ($post['amount'] ?? 0)
                ]
            );
            $this->db->query(
                'UPDATE wallets SET total_amount=:total_amount WHERE w_id=:w_id', 
                [
                    'w_id' => (int)$_SESSION['wallet']['w_id'],
                    'total_amount' => $_SESSION['wallet']['total_amount'] - ($post['amount'] ?? 0)
                ]
            );
            $_SESSION['wallet']['total_amount'] = $_SESSION['wallet']['total_amount'] - ($post['amount'] ?? 0);
            $_SESSION['wallet']['accumulated'] = $_SESSION['wallet']['accumulated'] + ($post['amount'] ?? 0);
        }else{
            $this->db->query(
                'UPDATE wallets SET total_amount=:total_amount WHERE w_id=:w_id', 
                [
                    'w_id' => (int)$_SESSION['wallet']['w_id'],
                    'total_amount' => $_SESSION['wallet']['total_amount'] - ($post['amount'] ?? 0)
                ]
            );
            $_SESSION['wallet']['total_amount'] = $_SESSION['wallet']['total_amount'] - ($post['amount'] ?? 0);
        }

        $this->db->query( 'INSERT INTO wasted VALUES (NULL, :wallet, :type, :amount, :date)', $params );

        return true;
    }
}