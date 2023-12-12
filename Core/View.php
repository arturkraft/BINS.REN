<?php

namespace Core;

/**
 * View
 *
 * PHP version 8.3
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = "../App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            //echo "$file not found";
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate(string $template, array $args = [])
    {
        static $twig = null;
 
        if ($twig === null)
        {
            $loader = new \Twig\Loader\FilesystemLoader('../App/Views');
            $twig = new \Twig\Environment($loader, [
            'cache' => '../App/Views/Cache',
        ]);
        }
 
        echo $twig->render($template, $args);

    }

    /**
     * Render an offline view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplateOffline(string $template, array $args = [])
    {
        static $twig = null;
 
        if ($twig === null)
        {
            $loader = new \Twig\Loader\FilesystemLoader('../App/Views');
            $twig = new \Twig\Environment($loader, [
            'cache' => '../App/Views/Cache',
        ]);
        }
 
        return $twig->render($template, $args);

    }
}
