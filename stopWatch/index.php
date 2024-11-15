<?php
set_time_limit(300);
class Stopwatch
{
    private $startTime;
    private $endTime;
    public function __construct($startTime = null)
    {
        if (is_null($startTime)) {
            $this->startTime = time();
        } else {

            $this->startTime = $startTime;
        }
    }
    public function getStartTime()
    {
        return $this->startTime;
    }
    public function getEndTime()
    {
        return $this->endTime;
    }
    public function start()
    {
        $this->startTime = time();
    }
    public function stop()
    {
        $this->endTime = time();
    }
    public function getElapsedTime()
    {
        return $this->endTime - $this->startTime;
    }
}

function selectionSort(&$arr)
{

    for ($i = 0; $i < count($arr) - 1; $i++) {
        $min = $i;
        for ($j = $i + 1; $j < count($arr); $j++) {
            if ($arr[$min] > $arr[$j]) {
                $min = $j;
            }
        }
        if ($min != $i) {
            $temp = $arr[$i];
            $arr[$i] = $arr[$min];
            $arr[$min] = $temp;
        }
    }
}

$array = [];

$so = 10000;
for ($i = 0; $i < $so; $i++) {
    $array[] = $i;
}


$tinhTime = new Stopwatch();
$tinhTime->start();
selectionSort($array);

$tinhTime->stop();
echo "thoi gian đo được của selection sort với  $so số là   :" . $tinhTime->getElapsedTime() . " giây";
