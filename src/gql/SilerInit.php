<?php namespace Play\Gql;

use Play\Data\Builder\Room;

class SilerInit
{
    /**
     * @var Room
     */
    private $data;

    public static function init() 
    {
        return new self();
    }

    public function __construct() 
    {
        $this -> data = Room::init();
        if(file_exists(Room::dataDir())){
            $handle = fopen(Room::dataDir(), "r");
            while(($line = fgets($handle)) !== FALSE) {
                $this -> data = unserialize($line);
            }
            fclose($handle);
        }
    }

    public function makeData($keys = [2265]) : Room 
    {
        $rooms = Room::init() -> make($keys);
        $handle = fopen(Room::dataDir(), "w");
        fwrite($handle, serialize($rooms));
        fclose($handle);
        return $rooms;
    }

    public static function schemaTypes(): string
    {
        return file_get_contents(__DIR__ . '/../config/schema.graphql');
    }

    public static function resolvers(): array
    {
        $t = self::init();

        return [
            'Query' => [
                'hello' => "Hello Guys!",
                'rooms' => function () use ($t) {
                    return $t -> getData();
                },
                'roomsHavingKey' => function ($root, $args) use ($t) {
                    return array_filter($t -> getData(), function ($v) use ($args) {
                        return in_array($v['key'], $args['search_keys']);
                    });
                },
                'roomsNotHavingKey' => function ($root, $args) use ($t) {
                    return array_filter($t -> getData(), function ($v) use ($args) {
                        return !in_array($v['key'], $args['search_keys']);
                    });
                }
            ],
            'Mutation' => [
                'makeRooms' => function ($root, $args) use ($t) {
                    $t -> data = $t -> makeData($args['keys']) -> getData();
                    return $t -> data;
                }
            ]
        ];
    }

    private function getData(): array
    {
        return $this -> data -> getData();
    }
}
