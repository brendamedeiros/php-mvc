<?php

namespace Core;

/**
 * View
 */

class View
{
    private static $twig = null;
    /**
     * Render a view file
     *
     * @param string $view The view file
     *
     * @param array $args
     * @return void
     * @throws \Exception
     */
    public static function render($view, $args = [])
    {
        // Importa variáveis para a tabela de símbolos a partir de um array
        extract($args, EXTR_SKIP);

        $file = "../App/Views/$view";  // relative to Core directory

        if (is_readable($file))
        {
            require $file;
        }
        else
        {
            throw new \Exception("{$file} not found");
        }
    }

    public static function renderTemplate($template, $args = [])
    {
        if (is_null(self::$twig))
        {
            $loader = new \Twig_Loader_Filesystem('../App/Views');
            self::$twig = new \Twig_Environment($loader);
        }

        echo self::$twig->render($template, $args);
    }
}
