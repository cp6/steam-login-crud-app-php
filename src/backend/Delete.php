<?php

namespace backend;

class Delete extends Insert
{

    protected function deleteAccount(string $uid, string $steam_id): bool
    {
        $delete = $this->db->prepare("DELETE FROM `accounts` WHERE `uid` = ? AND `steam_id` = ? LIMIT 1;");
        return $delete->execute([$uid, $steam_id]);
    }

}