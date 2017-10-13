<?php
namespace Ics\Parser;

class Calendar
{
    private $url;

    public $events = array();

    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * [parse description]
     * @return [type] [description]
     */
    public function parse()
    {
        $this->events = array();

        $lines = file($this->url, FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            throw new \Exception("Can't open file '$this->url'", 1);
        }

        $lines = array_map(
            // Supprime tous les caractères invisibles sauf l'espace.
            function ($line) {
                return trim($line, "\t\n\r\0\x0B");
            },
            $lines
        );

        foreach ($lines as $line) {
            switch ($line) {
                case 'BEGIN:VEVENT':
                    $event = new Event();
                    break;

                case 'END:VEVENT':
                    if (isset($event)) {
                        $this->events[] = $event;
                        unset($event);
                    }
                    break;

                default:
                    if (isset($event)) {
                        $event->addLine($line);
                    }
                    break;
            }
        }
    }

    public function getAllEvents()
    {
        return $this->events;
    }

    public function getEventsFromTo($dtStart, $dtEnd)
    {
        $selectedEvents = new Calendar($this->url);

        foreach ($this->events as $event) {
            if ($event->DTSTART >= $dtStart
                and $event->DTEND <= $dtEnd
            ) {
                $selectedEvents->events[] = $event;
            }
        }

        return $selectedEvents;
    }
}
