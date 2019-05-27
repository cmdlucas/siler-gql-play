<?php namespace Play\Data\Builder;

class Room
{
    private $data = [];

    public static function init() {
        return new Room();
    }

    public function make($keys)
    {
        foreach($keys as $key) {
            array_push($this -> data, [
                "info" => [
                    "keyToBin" => base_convert($key, 10, 2),
                    "timestamp" => time()
                ],
                "key" => $key
            ]);
        }
        return $this;
    }

    public function getData(): array {
        return $this -> data;
    }
    
    public static function dataDir() {
        return __DIR__ . "/../storage/data.txt";
    }
}