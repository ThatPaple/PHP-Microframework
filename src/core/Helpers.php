<?php
namespace App\Core;

class Helpers {
    public static function debug($data): void {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public static function sanitize(string $input): string {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}