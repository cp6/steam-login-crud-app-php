# Steam login CRUD app PHP

A PHP OOP psr-4 auto-loading CRUD app using Steam login authentication from [OpenId](https://openid.net/)
with [xPaw OpenId Steam auth](https://github.com/xPaw/SteamOpenID.php). Includes auto-loading classes for DB actions and
for pages.

## Features:

#### Pages:

* Home page (Different message depending on login status).
* Login page (Redirects if already logged in).
* Logout page.
* Account page (Redirects if NOT logged in).
* Delete account page (Redirects if NOT logged in).

#### Actions:

* Simple check for pages if user is logged in.
* Login (Stores: username, Steam id and first login date in DB).
* Update account (Username, last login and times logged in) on login.
* Delete account (Redirects if not logged in).

## Usage (setup)

* Run ```database.sql``` into your MySQL server.
* Add your MySQL details lines 18-21 ```src/General.php```.
* Add your Steam API key at line 7 ```src/Config.php```.

##### To check logged in:

```php 
        if ($this->isLoggedIn()) {
            //Is logged in
        } else {
            //Not logged in
        }
```

#### Creating page only logged in users can view:

```secret/index.php```:

```php 
<?php
require_once '../vendor/autoload.php';

use frontend\Pages;

$h = new Pages();
$h->secretPage();
```

```src/frontend/Pages.php```:

```php 
    public function secretPage(): void
    {
        if (!$this->isLoggedIn()) {//Not logged in
            $this->doHeader(self::URL);
        }
        echo "You can see this because you are logged in";
    }
```