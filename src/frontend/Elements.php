<?php

namespace frontend;

use backend\User;

class Elements extends User
{
    private function pageTitle(string $title): void
    {
        echo "<title>$title</title>";
    }

    private function pageDescription(string $description): void
    {
        echo "<meta name='description' content='$description'>";
    }

    protected function pageHeader(string $title, string $description, string $custom_css_file = ''): void
    {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <?= $this->pageTitle($title) ?>
            <?php
            $this->pageDescription($description);
            $this->CSSLinks();
            if (!empty($custom_css_file)) {
                echo "<link rel='stylesheet' href='" . self::ASSETS_URL . "/css/$custom_css_file'/>";
            }
            ?>
        </head>
        <body>
        <?php
    }

    private function CSSLinks(): void
    {
        echo "<link rel='stylesheet' href='" . self::ASSETS_URL . "/css/bootstrap.min.css'/>";
    }

    private function JSLinks(bool $jquery = true, bool $bootstrap = true): void
    {
        if ($jquery) {
            echo "<script src='" . self::ASSETS_URL . "/js/jquery.min.js'></script>";
        }
        if ($bootstrap) {
            echo "<script src='" . self::ASSETS_URL . "/js/bootstrap.min.js'></script>";
        }
    }

    protected function pageClose(string $custom_js_file = ''): void
    {
        $this->JSLinks();
        if (!empty($custom_js_file)) {
            echo "<script src='" . self::ASSETS_URL . "/js/$custom_js_file'></script>";
        }
        echo "</body></html>";
    }

    protected function inputLabel(string $text, string $for): void
    {
        echo "<label for='$for'>$text</label>";
    }

    protected function textInput(string $id_name, string $placeholder = '', string $value = '', int $max_length = 255, int $min_length = 6, bool $required = true): void
    {
        ($required) ? $req = ' required' : $req = '';
        (!empty($placeholder)) ? $plh = "placeholder='$placeholder'" : $plh = '';
        (!empty($value)) ? $v = "value='$value'" : $v = '';
        echo "<input class='form-control' type='text' id='$id_name' name='$id_name' $plh $v maxlength='$max_length' minlength='$min_length'$req>";
    }


}