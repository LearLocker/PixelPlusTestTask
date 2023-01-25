<?php

namespace SMA;

use DateTime;

class SimpleMovingAverage
{
    const DATA = 'Местное время в Москве (центр, Балчуг)';
    const TEMPERATURE = 'T';

    private array $day_set = [];
    private array $week_set = [];
    private array $month_set = [];
    private array $day_average = [];
    private array $week_average = [];
    private array $month_average = [];
    private array $source;

    public function __construct($source)
    {
        $this->source = $source;

        $this->dayAverage();
        $this->weekAverage();
        $this->monthAverage();
    }

    /**
     * @return array
     */
    public function getDayAverage(): array
    {
        return $this->day_average;
    }

    /**
     * @return array
     */
    public function getWeekAverage(): array
    {
        return $this->week_average;
    }

    /**
     * @return array
     */
    public function getMonthAverage(): array
    {
        return $this->month_average;
    }

    private function dayAverage()
    {
        foreach ($this->source as $data) {
            $date = strtotime($data[self::DATA]);
            if(!array_key_exists(self::TEMPERATURE, $data))
                continue;

            $this->day_set[date('d-m-Y', $date)][date('H:i', $date)] =
                $data[self::TEMPERATURE];
        }

        $this->day_average = $this->countAverage($this->day_set);
    }

    private function weekAverage()
    {
        foreach ($this->day_average as $day => $temp) {
            $date = strtotime($day);

            if (date('W', $date) > 52)
                continue;

            $this->week_set[date('W', $date)][date('d-m-Y', $date)] = $temp;
        }

        $this->week_average = $this->countAverage($this->week_set);
    }

    private function monthAverage()
    {
        foreach ($this->week_average as $week_num => $temp) {
            $date = (new DateTime())
                ->setISODate(2021, $week_num)
                ->format('m');

            $this->month_set[$date][$week_num] = $temp;
        }

        $this->month_average = $this->countAverage($this->month_set);
    }

    private function countAverage($set): array
    {
        $result = [];

        foreach ($set as $period => $stats) {
            $temp_array_length = count($stats);

            $temps = array_map(fn($temp) => floatval($temp), array_values($stats));
            $total_temp = array_sum($temps);

            $avg_temp = $total_temp / $temp_array_length;
            $result[$period] = round($avg_temp, 1);
        }

        return $result;
    }
}