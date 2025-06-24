<?php
use App\Core\Lang;

if (!function_exists('_t')) {
    function _t(string $key, array $replace = []): string {
        return Lang::get($key, $replace);
    }
}
