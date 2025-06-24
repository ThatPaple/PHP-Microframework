<?php
namespace App\Modules;

use App\Core\Helpers;

class Product {
    public function get(int $id): array {
        $id = Helpers::sanitize((string)$id);
        return [
            'id' => $id,
            'title' => 'Product #' . $id
        ];
    }
}
