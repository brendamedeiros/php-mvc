<?php

namespace Core;
/** Router */

class Router {
    /** Associative array of routes (the routing table) */

    protected $routes = [];
    protected $params = [];

    /** Add a route to the routing table
     *
     * @param string $route The route URL
     * @param array $params Parameters (controller, action ,etc.)
     *
     * @return void
     */

     public function add($route, $params = [])
     {
         //Convert the route to a regular expression: escape forward slashes
         $route = preg_replace('/\//', '\\/', $route);

         // Convert variables e.g. {controller}
         $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

         // Convert variables with custom regular expressions e.g {id:\d+}
         $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

         // Add start and end delimiters and case insensitive flag
         $route = "/^{$route}$/i";

         $this->routes[$route] = $params;
     }
    public function getParams()
    {
        return $this->params;
    }

     public function dispatch($url)
     {
         $url = $this->removeQueryStringVariables(($url));
         if ($this->match($url))
         {
             $controller = $this->params['controller'];
             $controller = $this->convertToStudlyCaps(($controller));
//             $controller = "App\Controllers\\{$controller}";
             $controller = $this->getNamespace() . $controller;

             if (class_exists($controller))
             {
                 $controller_obj = new $controller($this->params);

                 $action = $this->params['action'];
                 $action = $this->convertToCamelCase($action);

                 if (is_callable([$controller_obj, $action]))
                 {
                     $controller_obj->$action();
                 }
                 else
                 {
                     throw new \Exception("\"Method {$action} (in controller {$controller}) not found");
                 }
             }
             else
             {
                 throw new \Exception("Controller class {$controller} not found.");
             }
         }
         else
         {
             throw new \Exception("No route matched.", 404);
         }
     }

     protected function convertToStudlyCaps($string)
     {
         /** ucwords(): converte para maiúsculas o primeiro caractere de cada palavra */
         return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
     }

     protected function convertToCamelCase($string)
     {
         /** lcfirst() : torna minúsculo o primeiro caractere de uma string */
         return lcfirst($this->convertToStudlyCaps(($string)));
     }

     public function getRoutes()
     {
         return $this->routes;
     }

     public function match($url)
     {
        // $reg_exp = '/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/';

        foreach ($this->routes as $route => $params)
        {
            if (preg_match($route, $url, $matches))
            {
                // Get named capture group values
                foreach ($matches as $key => $match)
                {
                    if (is_string($key))
                    {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }

        return false;
     }

     protected function removeQueryStringVariables($url)
     {
         if ($url !== '')
         {
             $parts = explode('&', $url, 2);
             if (strpos($parts[0], '=') === false)
             {
                 $url = $parts[0];
             }
             else
             {
                 $url = '';
             }
         }

         return $url;
     }

     protected function getNamespace()
     {
         $namespace = 'App\Controllers\\';
         if (array_key_exists('namespace', $this->params))
         {
             $namespace .= $this->params['namespace'] . '\\';
         }

         return $namespace;
     }


}
