<?php


class Labyrinth {

    private static $DegFourth = 90;
    private static $DegHalf = 180;

    private static $North = 0;
    private static $East = 90;    
    private static $South = 180;
    private static $West = 270;

    private $dir;
    private $coords;
    private $data;

    function __construct() {
        $this->dir = self::$South;
        $this->coords = [0, -1];
        $this->data = [];
    }

    public function actionReverse($steps = '') {
        $this->dir = $this->relativeDir(self::$DegHalf);
        return $this->action($steps);
    }

    private function relativeDir($deg) {
        return (360 + $this->dir + $deg) % 360;
    }

    public function action($steps = '') {
        $stepsList = str_split(trim($steps));
        $stepsCount = count($stepsList);
        foreach($stepsList as $index => $step) {
            if(in_array($step, ['L', 'R'])) {
                $this->dir = $this->relativeDir(($step === 'L' ? -1 : 1) * self::$DegFourth);
            } else {
                list($coordNewX, $coordNewY) = $this->coords;

                if(in_array($this->dir, [self::$North, self::$South])) {
                    $coordNewY = $this->dir === self::$South ? $coordNewY + 1 : $coordNewY - 1;
                } else {
                    $coordNewX = $this->dir === self::$East ? $coordNewX + 1 : $coordNewX - 1;
                }

                $coordsNew = [$coordNewX, $coordNewY];
                
                if($index) {
                    $this->setData($this->coords, $this->dir);
                }                    

                if($index + 1 != $stepsCount) {
                    $this->setData($coordsNew, $this->relativeDir(self::$DegHalf));
                }               

                $this->coords = $coordsNew;
            }
        }

        return $this;
    }

    private function setData($coords, $dir) {
        list($x, $y) = $coords;

        if(!array_key_exists($y, $this->data)) {
            $this->data[$y] = [];
        }

        if(!array_key_exists($x, $this->data[$y])) {
            $this->data[$y][$x] = [               
                self::$East => 0, 
                self::$West => 0,                
                self::$South => 0,
                self::$North => 0,            
            ];
        }

        $this->data[$y][$x][$dir] = 1;
    }

    public function getData() {
        $minX = min(array_map(function($row) {
            return min(array_keys($row));
        }, $this->data));

        $maxX = max(array_map(function($row) {
            return max(array_keys($row));
        }, $this->data));

        $minY = min(array_keys($this->data));

        $maxY = max(array_keys($this->data));

        $result = [];
        for($y = $minY; $y <= $maxY; $y++) {
            $result[$y - $minY] = [];

            for($x = $minX; $x <= $maxX; $x++) {
                $binary = array_reduce($this->data[$y][$x] ?? [], function($carry, $item) {
                    return $carry.$item;
                }, '') ?: '0';

                $result[$y - $minY][] =dechex(bindec($binary));
            }
        }
        return $result;
    }
}