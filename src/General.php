<?php

class General extends Config
{
    public pdo $db;

    public function __construct()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $this->sessionStart();
        $this->db = $this->db_connect();
    }

    public function db_connect(): pdo
    {
        $db = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";
        $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        return new PDO($db, self::DB_USERNAME, self::DB_PASSWORD, $options);
    }

    protected function dateTimeFormat(string $dt, string $return_format = 'Y-m-d H:i:s'): string
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $dt);
        return $date->format($return_format);
    }

    protected function genString(int $length = 6): string
    {
        $character_pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $char_length = strlen($character_pool);
        $the_string = '';
        for ($i = 0; $i < $length; $i++) {
            $the_string .= $character_pool[random_int(0, $char_length - 1)];
        }
        return $the_string;
    }

    protected function sessionStart(): void
    {//Start session if none exists
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function sessionDestroy(): void
    {//Destroys session
        session_unset();
        session_destroy();
    }

    protected function isLoggedIn(): bool
    {//Checks if session is found, this is only set upon log in
        $this->sessionStart();//Start session if none already started
        return isset($_SESSION['uid']) && !empty($_SESSION['uid']);//Not logged in
    }

    protected function logout(bool $do_redirect = false, string $redirect_to = 'index.php'): void
    {//Kills the session and logs out the user
        $this->sessionDestroy();//Destroys session
        if ($do_redirect) {
            $this->doHeader($redirect_to);
        }
    }

    protected function doHeader(string $location = 'index.php', bool $do_exit = true): void
    {//Redirect with optional exit;
        header("Location: $location");
        if ($do_exit) {
            exit;
        }
    }

    protected function cleanUsername(string $username): string
    {//Cleans steam username
        return preg_replace('/[^a-zA-Z0-9.=_!?<>|()#-]/s', '', $username);
    }
}