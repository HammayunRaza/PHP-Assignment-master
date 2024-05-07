<?php
use DI\ContainerBuilder;
use DI\Container;
use FastRoute\Dispatcher;

require_once 'vendor/autoload.php'; // Include autoloader

$routeVars = function ($routeInfo) { return $routeInfo = [2];};

// $routeVars = function ($routeInfo) {
//     // Extract data from $routeInfo and return it
//     return $routeInfo[2]; // Assuming $routeInfo[2] holds the required data
// };



// Create DI container
// $app = new Container();

// Create DI container
$containerBuilder = new ContainerBuilder();
$app = $containerBuilder->build();

// Include database configuration
require_once __DIR__ . '/bootstrap/db.php';

// Include Redis configuration
$redis = require_once __DIR__ . '/bootstrap/redis.php';

$app->set('redis', $redis);

// Include models and repositories
require_once __DIR__ . '/bootstrap/repositories.php';
require_once __DIR__ . '/bootstrap/models.php';

// Load routes
$dispatcher = FastRoute\simpleDispatcher(require_once __DIR__ . '/bootstrap/routes.php');

// Fetch method and URI from the request
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
// Dispatch the request
// $postData = ['id' => '123', 'content' => 'Hello I am here', 'title' => "Title of teh post", 'author' => "Hammayun Raza"];
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
$routeInfo[2] = ['id' => '123', 'content' => 'Hello I am here', 'title' => "Title of teh post", 'author' => "Hammayun Raza"];
// Handle routing results
switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(['error' => '404 Not Found']);
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo json_encode(['error' => '405 Method Not Allowed']);
        break;
    case Dispatcher::FOUND:

        list($controllerName, $method) = $routeInfo[1];
        // print_r($routeInfo);
        // exit;
        $vars = call_user_func_array($routeVars, array_values($routeInfo[2]));
        // print_r($vars);
        // exit;

        // Convert namespace separators to directory separators
        $convertedControllerName = str_replace('\\', '/', $controllerName);
        // Require the controller file
        
        require_once __DIR__ . '/src/' . $convertedControllerName . '.php';

        // Create instance of the controller and call the method
        $controllerInstance = new $controllerName($app);
        
        $response = call_user_func_array([$controllerInstance, $method], $vars);

        // Set Content-Type header to indicate JSON response
        header('Content-Type: application/json');

        // Output the response as JSON
        echo json_encode($response);
        break;
}
