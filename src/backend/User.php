<?php

namespace backend;

class User extends Delete
{
    public string $uid;
    public string $steam_id;
    public string $username;
    public array $user_data;

    private function setUserData(): void
    {//Player's steam account data array
        $url = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . self::STEAM_API_KEY . "&steamids=" . $this->steam_id;
        $content = json_decode(file_get_contents($url), true, 512, JSON_THROW_ON_ERROR);
        if (isset($content['response']['players'][0])) {
            $pd = $content['response']['players'][0];
            $this->user_data = array(
                'steam_id' => $pd['steamid'],
                'name' => $pd['personaname'],
                'avatar' => $pd['avatarfull'],
                'avatar_med' => $pd['avatarmedium'],
                'state' => $pd['personastate'],
                'profile_state' => $pd['profilestate'],
                'url' => $pd['profileurl']
            );
            echo json_encode($this->user_data);
        } else {
            $this->user_data = array();
        }
    }

    protected function loggedIn(string $steam_id): void
    {
        $this->sessionStart();
        $_SESSION['steam_id'] = $this->steam_id = $steam_id;//Steam user id string
        $this->setUserData();
        $account_data = $this->accountData($this->steam_id);
        if (!empty($account_data)) {//Existing account
            $_SESSION['uid'] = $this->uid = $account_data['uid'];
            $_SESSION['username'] = $this->username = $account_data['username'];
            $this->updateAccountLogin();
        } else {//New, first time account
            $_SESSION['uid'] = $this->uid = $this->genString(6);
            $_SESSION['username'] = $this->username = $this->user_data['name'];
            $this->insertNewAccount($this->uid, $this->steam_id, $this->username);
            $this->saveAvatar();
        }
    }

    protected function OpenIdValidateLogin(string $SelfURL): ?string
    {
        // PHP automatically replaces dots with underscores in GET parameters
        // See https://www.php.net/variables.external#language.variables.external.dot-in-names
        if (filter_input(INPUT_GET, 'openid_mode') !== 'id_res') {
            return null;
        }

        // See http://openid.net/specs/openid-authentication-2_0.html#positive_assertions
        $Arguments = filter_input_array(INPUT_GET, [
            'openid_ns' => FILTER_SANITIZE_URL,
            'openid_op_endpoint' => FILTER_SANITIZE_URL,
            'openid_claimed_id' => FILTER_SANITIZE_URL,
            'openid_identity' => FILTER_SANITIZE_URL,
            'openid_return_to' => FILTER_SANITIZE_URL, // Should equal to url we sent
            'openid_response_nonce' => FILTER_SANITIZE_SPECIAL_CHARS,
            'openid_assoc_handle' => FILTER_SANITIZE_SPECIAL_CHARS, // Steam just sends 1234567890
            'openid_signed' => FILTER_SANITIZE_SPECIAL_CHARS,
            'openid_sig' => FILTER_SANITIZE_SPECIAL_CHARS
        ], true);

        if (!is_array($Arguments)) {
            return null;
        }

        foreach ($Arguments as $Value) {
            // An array value will be FALSE if the filter fails, or NULL if the variable is not set.
            // In our case we want everything to be a string.
            if (!is_string($Value)) {
                return null;
            }
        }

        if ($Arguments['openid_claimed_id'] !== $Arguments['openid_identity']
            || $Arguments['openid_op_endpoint'] !== 'https://steamcommunity.com/openid/login'
            || $Arguments['openid_ns'] !== 'http://specs.openid.net/auth/2.0'
            || !str_starts_with($Arguments['openid_return_to'], $SelfURL)
            || preg_match('/^https?:\/\/steamcommunity.com\/openid\/id\/(7656119[0-9]{10})\/?$/', $Arguments['openid_identity'], $CommunityID) !== 1
        ) {
            return null;
        }

        $Arguments['openid_mode'] = 'check_authentication';

        $c = curl_init();

        curl_setopt_array($c, [
            CURLOPT_USERAGENT => 'OpenID Verification',
            CURLOPT_URL => 'https://steamcommunity.com/openid/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 6,
            CURLOPT_TIMEOUT => 6,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $Arguments,
        ]);

        $Response = curl_exec($c);

        curl_close($c);

        if ($Response !== false && strrpos($Response, 'is_valid:true') !== false) {
            $this->loggedIn($CommunityID[1]);
            return $CommunityID[1];
        }

        return null;
    }

    private function saveAvatar(): void
    {
        file_put_contents("../av/{$_SESSION['steam_id']}.jpg", file_get_contents($this->user_data['avatar']));
    }

}