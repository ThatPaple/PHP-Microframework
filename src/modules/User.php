<?php
namespace App\Modules;

use App\Core\Helpers;

class User {
    public function get(int $id): array {
        $id = Helpers::sanitize((string)$id);
        return [
            'id' => $id,
            'name' => 'User #' . $id
        ];
    }
}
