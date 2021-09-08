<?php

namespace frontend;

use backend\OpenId;

class Pages extends Elements
{

    public function logoutPage(): void
    {
        $this->logout(true, self::URL);
    }

    public function accountPage(): void
    {
        if (!$this->isLoggedIn()) {//Not logged in
            $this->doHeader(self::URL);
        }
        $this->pageHeader('Account page', 'This is your account page');
        echo "This is your account page {$_SESSION['username']}!<br>";
        echo "Steam id: {$_SESSION['steam_id']}<br>";
        echo "User id: {$_SESSION['uid']}<br>";
        echo "<a href='" . self::URL . "/delete'>Delete account</a>";
        $this->pageClose();
    }

    public function deletePage(): void
    {
        if (!$this->isLoggedIn()) {//Not logged in
            $this->doHeader(self::URL);
        } else {
            $this->pageHeader('Delete Page', 'This is the delete account page');
            if ($this->deleteAccount($_SESSION['uid'], $_SESSION['steam_id'])) {
                $this->logout(false);//Kill the session
                echo "Account deleted";
            } else {
                echo "Error deleting account";
            }
            $this->pageClose();
        }
    }

    public function editAccountPage(): void
    {
        if (!$this->isLoggedIn()) {//Not logged in
            $this->doHeader(self::URL);
        } else {
            $this->pageHeader('Edit account Page', 'This is the edit account page');
            if (isset($_POST['submit-form'])) {
                if ($this->updateAccountEmail($_POST['email_address'])) {
                    echo "<b>Updated your email address</b>";
                } else {
                    echo "<b>Error updating your email address</b>";
                }
            }
            $ac = $this->accountData($_SESSION['steam_id']);
            (is_null($ac['email'])) ? $email = '' : $email = $ac['email'];
            echo "<form id='edit-account-form' method='post'>";
            $this->inputLabel('Email address: ', 'email_address');
            $this->textInput('email_address', '', $email);
            echo "<br>";
            echo "<input type='submit' name='submit-form' value='Updated'>";
            echo "</form>";
            $this->pageClose();
        }
    }

    public function homePage(): void
    {
        $this->pageHeader('Home page', 'This is the home page');
        if (!$this->isLoggedIn()) {
            echo "You are not logged in. <a href='login/'>Login here</a>";
        } else {
            $username = $_SESSION['username'];
            echo "Hello $username! <br> You can logout <a href='logout/'>here</a>.";
        }
        $this->pageClose();
    }

    public function loginPage(): void
    {
        $this->pageHeader('Login page', 'This is where you log in');
        if (isset($_GET['openid_claimed_id'])) {
            $CommunityID = $this->OpenIdValidateLogin(self::URL);
            if ($CommunityID !== null) {//Login succeeded, $CommunityID is the 64-bit SteamID
                $this->loggedIn($CommunityID);
                $this->doHeader(self::URL);
            }
        } elseif ($this->isLoggedIn()) {
            //Already Logged in...redirect to home page
            $this->doHeader(self::URL);
        }
        ?>
        <h1>Login</h1>
        <p>Login securely using Steam with <a href='https://openid.net/'>OpenID</a>.
            We do not see or store your password.</p>
        <form action="https://steamcommunity.com/openid/login" method="post">
            <input type="hidden" name="openid.identity"
                   value="http://specs.openid.net/auth/2.0/identifier_select">
            <input type="hidden" name="openid.claimed_id"
                   value="http://specs.openid.net/auth/2.0/identifier_select">
            <input type="hidden" name="openid.ns"
                   value="http://specs.openid.net/auth/2.0">
            <input type="hidden" name="openid.mode" value="checkid_setup">
            <input type="hidden" name="openid.realm" value="<?= self::URL ?>/login">
            <input type="hidden" name="openid.return_to"
                   value="<?= self::URL ?>/login">
            <input type="image" name="submit"
                   src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png"
                   border="0"
                   alt="Submit">
        </form>
        <?php
        $this->pageClose();
    }
}