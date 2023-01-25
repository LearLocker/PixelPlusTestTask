<?php

namespace InputData;

class Input
{
    private string $file_name;

    public function __construct($file_name)
    {
        $this->file_name = $file_name;
    }

    public function getStatistics(): array
    {
        $data = [];
        $headline = null;

        if (($h = fopen("{$this->file_name}", "r")) !== FALSE) {
            while (($row = fgetcsv($h, 1000, ";")) !== FALSE) {
                if ($headline === null) {
                    $headline = $row;
                    continue;
                }

                $data[] = $this->combineArr($headline, $row);
            }

            fclose($h);
        }

        return $data;
    }

    private function combineArr($a, $b)
    {
        $a_count = count($a);
        $b_count = count($b);

        $size = ($a_count > $b_count) ? $b_count : $a_count;

        $a = array_slice($a, 0, $size);
        $b = array_slice($b, 0, $size);

        return array_combine($a, $b);
    }
}