<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/core/Helpers.php';
require_once __DIR__ . '/../src/core/Translate.php';

use App\Core\Config;
use App\Core\Functions;
use App\Core\Helpers;
use App\Core\Lang;
use App\Core\Router;
use App\core\View;
use App\Modules\Product;
use App\Modules\User;

// Detect and load user language
$lang = Lang::detectLocale();
Lang::load($lang);

$router = Router::getInstance();

$router->loadMiddlewareFrom(__DIR__ . '/../src/Middleware');

$router->get('/', function () {
    View::render('home', ['title' => _t('nav.home')]);
});




//      Example of post.
//
//    $router->post('/login', function () {
//        $username = $_POST['username'] ?? '';
//        $password = $_POST['password'] ?? '';
//    
//        if ($username === 'admin' && $password === 'admin') {
//            $_SESSION['user_id'] = $username;
//            $_SESSION['user_role'] = 'admin';
//            header('Location: /admin');
//            exit;
//        }
//    
//        View::render('login', ['error' => 'Invalid credentials']);
//    });


//      Example of a role locked view
// 
//    $router->get('/admin', [['auth', 'role:admin'], function () {
//        View::render('admin', ['title' => 'Admin Dashboard']);
//    }]);


//      Example of modules, something to work with later perhaps..
//
//    $router->get('/product/{id}', function ($id) {
//       $product = (new Product())->get((int) $id);
//       return json_encode($product);
//    });

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
