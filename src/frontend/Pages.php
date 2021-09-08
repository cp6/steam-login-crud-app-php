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
        $account_data = $this->accountData($_SESSION['steam_id']);
        $this->pageHeader('Account page', 'This is your account page');
        echo "<div class='container'>";
        echo "<div class='row text-center'>";
        echo "<p>This is your account page {$_SESSION['username']}!</p>";
        echo "<p>Steam id: <i>{$_SESSION['steam_id']}</i></p>";
        echo "<p>User id: <i>{$_SESSION['uid']}</i></p>";
        echo "<p>Key: <i>{$account_data['key']}</i></p>";
        echo "<p>Email: <i>{$account_data['email']}</i> <a href='edit/'>edit</a></p>";
        echo "<p>First login: <i>{$this->dateTimeFormat($account_data['first_login'], 'g:i:sa l jS F Y')}</i></p>";
        echo "<p>Last login: <i>{$this->dateTimeFormat($account_data['last_login'], 'g:i:sa l jS F Y')}</i></p>";
        echo "<p>Times logged in: <i>{$account_data['times_logged_in']}</i></p>";
        echo "<p><a href='" . self::URL . "/delete'>Delete account</a></p>";
        echo "</div></div>";
        $this->pageClose();
    }

    public function deletePage(): void
    {
        if (!$this->isLoggedIn()) {//Not logged in
            $this->doHeader(self::URL);
        } else {
            $this->pageHeader('Delete Page', 'This is the delete account page');
            echo "<div class='container'>";
            echo "<div class='row text-center'>";
            if ($this->deleteAccount($_SESSION['uid'], $_SESSION['steam_id'])) {
                $this->logout(false);//Kill the session
                echo "<p>Account deleted</p>";
            } else {
                echo "<p>Error deleting account</p>";
            }
            echo "</div></div>";
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
            echo "<div class='container'>";
            echo "<div class='row'>";
            $ac = $this->accountData($_SESSION['steam_id']);
            (is_null($ac['email'])) ? $email = '' : $email = $ac['email'];
            echo "<form id='edit-account-form' method='post'>";
            echo "<div class='row'>";
            echo "<div class='col-12 col-md-6'>";
            $this->inputLabel('Email address: ', 'email_address');
            $this->textInput('email_address', '', $email);
            echo "</div>";
            echo "<div class='col-12 col-md-6 mt-4'>";
            echo "<input type='submit' name='submit-form' value='Update'>";
            echo "</div>";
            echo "</div>";
            echo "</form>";
            echo "</div></div>";
            $this->pageClose();
        }
    }

    public function homePage(): void
    {
        $this->pageHeader('Home page', 'This is the home page');
        echo "<div class='container'>";
        echo "<div class='row text-center'>";
        if (!$this->isLoggedIn()) {
            echo "<p>You are not logged in. <a href='login/'>Login here</a></p>";
        } else {
            $username = $_SESSION['username'];
            echo "<p>Hello $username! <br> You can logout <a href='logout/'>here</a> or go to <a href='account/'>account page</a>.</p>";
        }
        echo "</div></div>";
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
        <div class='container'>
            <div class='row text-center'>
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
            </div>
        </div>
        <?php
        $this->pageClose();
    }
}