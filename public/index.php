<?php

/**
 * Front controller

*/

//  echo 'Requested URL = ' . $_SERVER['QUERY_STRING'] . '"';

//require '../App/Controllers/Posts.php';
///**
// * Routing
// */
//
//require '../Core/Router.php';

/**  Autoloader */
require '../vendor/autoload.php';
//require '../vendor/mustache/mustache/src/Mustache/Autoloader.php';

/** Twig */
Twig_Autoloader::register();

/**
 * Autoloader - not being used now because the autoloader is being generated from composer
 */
//spl_autoload_register(function($class)
//{
//    $root = dirname(__DIR__);  //get the parent directory
//    $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
//
//    if (is_readable(($file)))
//    {
//        require $root . '/' . str_replace('\\', '/', $class). '.php';
//    }
//});

/**
 * Error and Exception Handler
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

$router = new Core\Router();
// echo get_class($router);

// Add the routes
// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);


// Display the routing table
//echo '<pre>';
//// var_dump($router->getRoutes());
//echo htmlspecialchars(print_r($router->getRoutes(), true));
//echo '</pre>';

// Match the requested route
//$url = $_SERVER['QUERY_STRING'];
//if ($router->match($url))
//{
//    print_r($router->getParams());
//}
//else
//{
//    echo "No route found for the URL {$url}";
//}

$router->dispatch($_SERVER['QUERY_STRING']);
