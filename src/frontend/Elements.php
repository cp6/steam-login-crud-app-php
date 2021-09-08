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

    protected function pageHeader(string $title, string $description): void
    {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <?= $this->pageTitle($title) ?>
            <?= $this->pageDescription($description) ?>
        </head>
        <body>
        <?php
    }

    protected function pageClose(): void
    {
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
        echo "<input class='form-control' type='text' id='$id_name' name='$id_name' $plh $v maxlength='$max_length' minlength='$min_length'$req></input";
    }


}