<?php
namespace App\Middleware;
use App\Core\Functions;

class AuthMiddleware
{
    public function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo '401 Unauthorized — Please log in first.';
            exit;
        }
    }
}
