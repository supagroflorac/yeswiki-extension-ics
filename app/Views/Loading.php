<?php
namespace Ics\Views;

class Loading extends View
{
    private $src;
    private $templateToLoad;

    public function __construct($src, $templateToLoad)
    {
        parent::__construct();
        $this->template = 'loading';
        $this->templateToLoad = $templateToLoad;
        $this->src = $src;
    }

    protected function grabInformations()
    {
        $infos = array(
            'src' => urlencode($this->src),
            'template' => $this->templateToLoad,
        );
        return $infos;
    }
}
