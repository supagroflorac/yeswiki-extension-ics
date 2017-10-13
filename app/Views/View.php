<?php
namespace Ics\Views;

abstract class View
{
    private $twig;

    protected $template = "loading";

    public function __construct()
    {
        $viewsPath = 'views/';
        if (is_dir('tools/ics/views')) {
            $viewsPath = 'tools/ics/views';
        }

        $twigLoader = new \Twig_Loader_Filesystem($viewsPath);
        $this->twig = new \Twig_Environment($twigLoader);
    }

    public function show()
    {
        $infos = $this->grabInformations();
        echo $this->twig->render($this->template . ".twig", $infos);
    }

    abstract protected function grabInformations();
}
