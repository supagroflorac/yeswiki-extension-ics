<?php
namespace Ics;

/* Usage : {{cal src="http://mon.domai.ne/path/fichier.ics" [template='minical']}} */

$loader = require __DIR__ . '/vendor/autoload.php';

$month = 'NOW';
if (isset($_GET['month'])) {
    $month = filter_var($_GET['month'], FILTER_SANITIZE_NUMBER_INT);
}

$src = filter_var(urldecode($_GET['src']), FILTER_SANITIZE_URL);
$template = filter_var($_GET['template'], FILTER_SANITIZE_STRING);

$calendar = new Parser\Calendar($src);
$calendar->parse();

$calendar->cutMultiDaysEvent();

$firstDayOfMonth = (new \DateTime($month))
    ->modify('first day of this month')
    ->setTime(0, 0, 0);

$lastDayOfMonth = (new \DateTime($month))
    ->modify('last day of this month')
    ->setTime(23, 59, 59);

$selectedEvents = $calendar->getEventsFromTo($firstDayOfMonth, $lastDayOfMonth);

$view = new Views\AjaxMonthCalendar($src, $firstDayOfMonth, $lastDayOfMonth, $selectedEvents, $template);
$view->show();
