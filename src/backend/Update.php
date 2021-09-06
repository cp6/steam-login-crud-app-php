<?php

namespace backend;

class Update extends Select
{
    protected function updateAccountLogin(): bool
    {
        $update = $this->db->prepare("UPDATE `accounts` SET `last_login` = NOW(), `times_logged_in` = (`times_logged_in` + 1), `username` = ? WHERE `steam_id` = ? LIMIT 1;");
        return $update->execute([$this->cleanUsername($_SESSION['username']), $_SESSION['steam_id']]);
    }
}