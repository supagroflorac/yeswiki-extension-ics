<?php
namespace Ics\Views;

class AjaxMonthCalendar extends View
{
    public function __construct($src, $dtStart, $dtEnd, $events, $template)
    {
        parent::__construct();
        $this->src = $src;
        $this->template = $template;
        $this->events = $events;
        $this->dtStart = $dtStart;
        $this->dtEnd = $dtEnd;
    }

    protected function grabInformations()
    {
        $infos = array(
            'title' => $this->dtStart->format('F Y'),
            'calendar' => $this->build(),
            'nextMonth' => $this->makeUrl($this->nextMonth()),
            'prevMonth' => $this->makeUrl($this->prevMonth()),
        );
        return $infos;
    }

    private function makeUrl($tsMonth)
    {
        return 'tools/ics/show.php?src='
            . $this->src . '&template='
            . $this->template . '&month='
            . $tsMonth;
    }

    private function nextMonth()
    {
        return (new \DateTime($this->dtStart->format(('Y-m'))))
            ->modify('+1 month')
            ->format('Y-m');
    }

    private function prevMonth()
    {
        return (new \DateTime($this->dtStart->format(('Y-m'))))
            ->modify('-1 month')
            ->format('Y-m');
    }

    private function build()
    {
        $formatedCalendar = array(
            0 => array(
                array('number' => 'L'),
                array('number' => 'M'),
                array('number' => 'M'),
                array('number' => 'J'),
                array('number' => 'V'),
                array('number' => 'S'),
                array('number' => 'D'),
            ),
            1 => array(),
        );

        $firstDay = (int)$this->dtStart->format('N');
        $lastDay = (int)$this->dtEnd->format('j');

        $week = 1;

        // Chaine vide dans les jours de la semaine qui font pas partie du mois
        for ($day = 1; $day < $firstDay; $day++) {
            $formatedCalendar[$week][] = '';
        }

        // Les jours du mois.
        for ($day=1; $day <= $lastDay; $day++) {
            if (count($formatedCalendar[$week]) > 6) {
                $week++;
                $formatedCalendar[$week] = array();
            }

            $number = (string)$day;
            if ($day < 10) {
                $number = '0' . $day;
            }

            $formatedCalendar[$week][] = array(
                'number' => $number,
                'texts' => $this->getEventsDescription($day),
            );
        }

        // Complète le mois avec des chaines vides
        while (count($formatedCalendar[$week]) < 7) {
            $formatedCalendar[$week][] = '';
        }

        return $formatedCalendar;
    }

    private function getEventsDescription($day)
    {
        $dayStart = (new \DateTime($this->dtStart->format("m/$day/Y")))
            ->setTime(0, 0, 0);
        $dayEnd = (new \DateTime($dayStart->format("m/d/Y")))
            ->modify('+1 day');

        $curDayEvents = $this->events->getEventsFromTo($dayStart, $dayEnd);

        $texts = array();
        foreach ($curDayEvents->events as $event) {
            $text = "";
            if ($event->DTSTART->format('H\hi') !== "00h00") {
                $text =$event->DTSTART->format('H\hi') . " : ";
            }

            $texts[] = $text . $event->SUMMARY;
        }

        return $texts;
    }
}
