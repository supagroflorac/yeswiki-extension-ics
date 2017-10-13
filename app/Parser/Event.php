<?php
namespace Ics\Parser;

class Event
{
    private $data = array();

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function addLine(string $line)
    {
        $explodedLine = explode(':', $line, 2);
        // Des fois des parametres sont ajouté comme le timezone pour DTEND
        // et DTSTART
        $parameter = explode(';', $explodedLine[0])[0];

        // La ligne commence par un espace : c'est la suite du résumé (SUMMARY).
        $lineFirstChar = substr($line, 0, 1);
        if ($lineFirstChar=== ' ' and isset($this->data['SUMMARY'])) {
            $this->data['SUMMARY'] .= substr($line, 1);
            return;
        }

        // Pour les dates le traitement est un peu différent
        if ($parameter === 'DTSTART' or $parameter === 'DTEND') {
            $this->data[$parameter] = new \DateTime($explodedLine[1]);
            return;
        }

        // Le résumé (première ligne.)
        if ($parameter === 'SUMMARY') {
            $this->data[$parameter] = $explodedLine[1];
            return;
        }
        // Les autres informations sont ignorées (inutiles actuellement)
    }
}
