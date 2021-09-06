<?php

namespace backend;

class Insert extends Update
{

    protected function insertNewAccount(string $uid, string $steam_id, string $username): bool
    {
        $key = $this->genString(32);
        $insert = $this->db->prepare('INSERT INTO `accounts` (`uid`, `steam_id`, `username`, `key`) VALUES (?, ?, ?, ?);');
        return $insert->execute([$uid, $steam_id, $this->cleanUsername($username), $key]);
    }

}