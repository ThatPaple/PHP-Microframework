<?php
namespace App\Core;

use App\core\View;

class Router
{
    private static ?Router $instance = null;

    protected array $routes = [];
    protected array $middlewareRegistry = [];
    protected array $middlewareGroups = [];
    protected array $globalMiddleware = [];
    protected array $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

    protected ?string $lastMatchedRoute = null;
    protected array $lastRouteParams = [];

    private function __construct()
    {
    }

    public static function getInstance(): Router
    {
        if (self::$instance === null) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    public function __call($method, $args)
    {
        $method = strtoupper($method);
        if (!in_array($method, $this->methods)) {
            throw new \BadMethodCallException("Unsupported method: {$method}");
        }

        [$path, $handlerOrArray] = $args;

        if (is_array($handlerOrArray) && count($handlerOrArray) === 2) {
            [$middleware, $handler] = $handlerOrArray;

            // Expand group middleware if any
            $middlewareList = [];
            foreach ((array) $middleware as $m) {
                if (isset($this->middlewareGroups[$m])) {
                    $middlewareList = array_merge($middlewareList, $this->middlewareGroups[$m]);
                } else {
                    $middlewareList[] = $m;
                }
            }

            $this->routes[$method][$path] = [
                'middleware' => $middlewareList,
                'handler' => $handler
            ];
        } else {
            $this->routes[$method][$path] = [
                'middleware' => [],
                'handler' => $handlerOrArray
            ];
        }
    }

    public function addMiddleware(string $name, callable $callback): void
    {
        $this->middlewareRegistry[$name] = $callback;
    }

    public function addMiddlewareGroup(string $groupName, array $middlewareNames): void
    {
        $this->middlewareGroups[$groupName] = $middlewareNames;
    }

    public function addGlobalMiddleware(callable $callback): void
    {
        $this->globalMiddleware[] = $callback;
    }

    public function loadMiddlewareFrom(string $dir, string $namespace = 'App\\Middleware\\'): void
    {
        foreach (glob($dir . '/*.php') as $file) {
            $name = basename($file, '.php');
            $class = $namespace . $name;

            if (!class_exists($class)) {
                require_once $file;
            }

            $key = strtolower(str_replace('Middleware', '', $name));

            $this->middlewareRegistry[$key] = function (...$params) use ($class) {
                $instance = new $class();
                if (method_exists($instance, 'handle')) {
                    $instance->handle(...$params);
                } elseif (is_callable($instance)) {
                    $instance(...$params);
                } else {
                    throw new \Exception("Middleware {$class} must have handle() or __invoke()");
                }
            };
        }
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $method = strtoupper($method);

        if (!isset($this->routes[$method])) {
            http_response_code(405);
            echo '405 Method Not Allowed';
            return;
        }

        foreach ($this->routes[$method] as $route => $config) {
            $pattern = preg_replace('#\{[a-zA-Z0-9_]+\}#', '([a-zA-Z0-9_]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                $this->lastMatchedRoute = $route;
                $this->lastRouteParams = $matches;

                foreach ($this->globalMiddleware as $mw) {
                    $mw();
                }

                foreach ($config['middleware'] as $middlewareEntry) {
                    if (str_contains($middlewareEntry, ':')) {
                        [$name, $params] = explode(':', $middlewareEntry, 2);
                        $args = explode(',', $params);
                    } else {
                        $name = $middlewareEntry;
                        $args = [];
                    }

                    if (isset($this->middlewareRegistry[$name])) {
                        $this->middlewareRegistry[$name](...$args);
                    } else {
                        throw new \Exception("Middleware '{$name}' not registered.");
                    }
                }

                echo call_user_func_array($config['handler'], $matches);
                return;
            }
        }

        http_response_code(404);
        View::render('hterrors/404');
    }

    public function getRoute(): ?array
    {
        if ($this->lastMatchedRoute === null) {
            return null;
        }
        return [
            'route' => $this->lastMatchedRoute,
            'params' => $this->lastRouteParams,
        ];
    }
}
