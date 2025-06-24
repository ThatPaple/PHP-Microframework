<?php
namespace App\Middleware;

class RoleMiddleware
{
    public function handle(string $requiredRole)
    {
        session_start();
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $requiredRole) {
            http_response_code(403);
            echo '403 Forbidden — You do not have permission to access this page.';
            exit;
        }
    }
}
