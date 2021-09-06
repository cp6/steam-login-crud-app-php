<?php

namespace backend;

use General;
use pdo;

class Select extends General
{

    protected function accountData(string $steam_id): array
    {
        $select = $this->db->prepare("SELECT `uid`, `steam_id`, `username` FROM `accounts` WHERE `steam_id` = ? LIMIT 1;");
        $select->execute([$steam_id]);
        $row = $select->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {
            return $row;
        }
        return array();//Empty
    }
}