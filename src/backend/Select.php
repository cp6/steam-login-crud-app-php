<?php

namespace backend;

use General;
use pdo;

class Select extends General
{

    protected function accountData(string $steam_id): array
    {
        $select = $this->db->prepare("SELECT `uid`, `steam_id`, `username`, `email` FROM `accounts` WHERE `steam_id` = ? LIMIT 1;");
        $select->execute([$steam_id]);
        $row = $select->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {
            return $row;
        }
        return array();//Empty
    }

    protected function doesKeyExist(string $key): bool
    {
        $select = $this->db->prepare("SELECT `key` FROM `accounts` WHERE `key` = ? LIMIT 1;");
        $select->execute([$key]);
        $row = $select->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {//Row found
            return true;
        } else {//NO row found
            return false;
        }
    }

    protected function doesUidExist(string $uid): bool
    {
        $select = $this->db->prepare("SELECT `uid` FROM `accounts` WHERE `uid` = ? LIMIT 1;");
        $select->execute([$uid]);
        $row = $select->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {//Row found
            return true;
        } else {//NO row found
            return false;
        }
    }
}