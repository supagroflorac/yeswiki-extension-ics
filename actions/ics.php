<?php
namespace Ics;

/* Usage : {{cal src="http://mon.domai.ne/path/fichier.ics" [template='minical']}} */

$loader = require __DIR__ . '/../vendor/autoload.php';

if (!defined("WIKINI_VERSION")) {
    die("acc&egrave;s direct interdit");
}

$src = filter_var($this->GetParameter("src"), FILTER_SANITIZE_URL);
$template = filter_var($this->GetParameter("template"), FILTER_SANITIZE_STRING);

if ($template === "") {
    $template = 'minical';
}

$view = new Views\Loading($src, $template);
$view->show();
