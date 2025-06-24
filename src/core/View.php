<?php
namespace App\Core;
use App\Core\Functions;

class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data); // turn keys into variables
        $viewPath = __DIR__ . "/../views/{$view}.php";

        if (!file_exists($viewPath)) {
            die("View {$view} not found.");
        }

        // Start output buffering and include layout
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        include __DIR__ . '/../views/layout.php';
    }
}
