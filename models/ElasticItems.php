<?php
namespace frontend\modules\svadbanaprirode\models;

use common\models\Restaurants;

class ElasticItems extends \yii\elasticsearch\ActiveRecord
{
    public function attributes()
    {
        return [
            'restaurant_id',
            'restaurant_gorko_id',
            'restaurant_price',
            'restaurant_min_capacity',
            'restaurant_max_capacity',
            'restaurant_district',
            'restaurant_parent_district',
            'restaurant_alcohol',
            'restaurant_firework',
            'restaurant_name',
            'restaurant_address',
            'restaurant_cover_url',
            'restaurant_latitude',
            'restaurant_longitude',
            'restaurant_own_alcohol',
            'restaurant_cuisine',
            'restaurant_parking',
            'restaurant_extra_services',
            'restaurant_payment',
            'restaurant_special',
            'restaurant_phone',
            'restaurant_location_sea',
            'restaurant_location_river',
            'restaurant_location_lake',
            'restaurant_location_mount',
            'restaurant_location_city',
            'restaurant_location_center',
            'restaurant_location_outside',
            'restaurant_commission',
            'id',
            'gorko_id',
            'restaurant_id',
            'price',
            'capacity_reception',
            'capacity',
            'type',
            'rent_only',
            'banquet_price',
            'bright_room',
            'separate_entrance',
            'type_name',
            'name',
            'features',
            'cover_url',
            'images',
            'thumbs',
            'description'
        ];
    }

    public static function index() {
        return 'pmn_sp_rooms';
    }
    
    public static function type() {
        return 'items';
    }

    /**
     * @return array This model's mapping
     */
    public static function mapping()
    {
        return [
            static::type() => [
                'properties' => [
                    'restaurant_id'                    => ['type' => 'integer'],
                    'restaurant_gorko_id'              => ['type' => 'integer'],
                    'restaurant_price'                 => ['type' => 'integer'],
                    'restaurant_min_capacity'          => ['type' => 'integer'],
                    'restaurant_max_capacity'          => ['type' => 'integer'],
                    'restaurant_district'              => ['type' => 'integer'],
                    'restaurant_parent_district'       => ['type' => 'integer'],
                    'restaurant_alcohol'               => ['type' => 'integer'],
                    'restaurant_firework'              => ['type' => 'integer'],
                    'restaurant_name'                  => ['type' => 'text'],
                    'restaurant_address'               => ['type' => 'text'],
                    'restaurant_cover_url'             => ['type' => 'text'],
                    'restaurant_latitude'              => ['type' => 'text'],
                    'restaurant_longitude'             => ['type' => 'text'],
                    'restaurant_own_alcohol'           => ['type' => 'text'],
                    'restaurant_cuisine'               => ['type' => 'text'],
                    'restaurant_parking'               => ['type' => 'text'],
                    'restaurant_extra_services'        => ['type' => 'text'],
                    'restaurant_payment'               => ['type' => 'text'],
                    'restaurant_special'               => ['type' => 'text'],
                    'restaurant_phone'                 => ['type' => 'text'],
                    'restaurant_location_sea'          => ['type' => 'integer'],
                    'restaurant_location_river'        => ['type' => 'integer'],
                    'restaurant_location_lake'         => ['type' => 'integer'],
                    'restaurant_location_mount'        => ['type' => 'integer'],
                    'restaurant_location_city'         => ['type' => 'integer'],
                    'restaurant_location_center'       => ['type' => 'integer'],
                    'restaurant_location_outside'      => ['type' => 'integer'],
                    'restaurant_commission'            => ['type' => 'integer'],
                    'id'                    => ['type' => 'integer'],
                    'gorko_id'              => ['type' => 'integer'],
                    'restaurant_id'         => ['type' => 'integer'],
                    'price'                 => ['type' => 'integer'],
                    'capacity_reception'    => ['type' => 'integer'],
                    'capacity'              => ['type' => 'integer'],
                    'type'                  => ['type' => 'integer'],
                    'rent_only'             => ['type' => 'integer'],
                    'banquet_price'         => ['type' => 'integer'],
                    'bright_room'           => ['type' => 'integer'],
                    'separate_entrance'     => ['type' => 'integer'],
                    'type_name'             => ['type' => 'text'],
                    'name'                  => ['type' => 'text'],
                    'features'              => ['type' => 'text'],
                    'cover_url'             => ['type' => 'text'],
                    'description'           => ['type' => 'text'],
                    'images'                => ['type' => 'nested', 'properties' =>[
                        'id'                => ['type' => 'integer'],
                        'sort'              => ['type' => 'integer'],
                        'realpath'          => ['type' => 'text'],
                        'subpath'           => ['type' => 'text'],
                        'waterpath'         => ['type' => 'text'],
                        'timestamp'         => ['type' => 'text'],
                    ]],
                    'thumbs'                => ['type' => 'nested', 'properties' =>[
                        'id'                => ['type' => 'integer'],
                        'sort'              => ['type' => 'integer'],
                        'realpath'          => ['type' => 'text'],
                        'subpath'           => ['type' => 'text'],
                        'waterpath'         => ['type' => 'text'],
                        'timestamp'         => ['type' => 'text'],
                    ]],

                ]
            ],
        ];
    }

    /**
     * Set (update) mappings for this model
     */
    public static function updateMapping()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->setMapping(static::index(), static::type(), static::mapping());
    }

    /**
     * Create this model's index
     */
    public static function createIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->createIndex(static::index(), [
            'settings' => [
                'number_of_replicas' => 0,
                'number_of_shards' => 1,
            ],
            'mappings' => static::mapping(),
        ]);
    }

    /**
     * Delete this model's index
     */
    public static function deleteIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->deleteIndex(static::index(), static::type());
    }

    public static function refreshIndex() {
        $res = self::deleteIndex();
        $res = self::updateMapping();
        $res = self::createIndex();
        $restaurants = Restaurants::find()
            ->with('rooms')
            ->limit(100000)
            ->all();
        foreach ($restaurants as $restaurant) {
            foreach ($restaurant->rooms as $room) {
                $res = self::addRecord($room, $restaurant);
            }            
        }
        echo 'Обновление индекса '. self::index().' '. self::type() .' завершено<br>';
    }

    public static function softRefreshIndex() {
        $restaurants = Restaurants::find()
            ->with('rooms')
            ->limit(100000)
            ->where(['in_elastic' => 0, 'active' => 1])
            ->all();
        foreach ($restaurants as $restaurant) {
            foreach ($restaurant->rooms as $room) {
                $res = self::addRecord($room, $restaurant);
            }  

            $restaurant->in_elastic = 1;
            $restaurant->save();
        }
        echo 'Обновление индекса '. self::index().' '. self::type() .' завершено<br>';
    }

    public static function addRecord($room, $restaurant){
        $isExist = false;
        
        try{
            $record = self::get($room->id);
            if(!$record){
                $record = new self();
                $record->setPrimaryKey($room->id);
            }
            else{
                $isExist = true;
            }
        }
        catch(\Exception $e){
            $record = new self();
            $record->setPrimaryKey($room->id);
        }

        $record->id  = $model->id;
        
        $record->restaurant_id = $restaurant->id;
        $record->restaurant_gorko_id = $restaurant->gorko_id;
        $record->restaurant_price = $restaurant->price;
        $record->restaurant_min_capacity = $restaurant->min_capacity;
        $record->restaurant_max_capacity = $restaurant->max_capacity;
        $record->restaurant_district = $restaurant->district;
        $record->restaurant_parent_district = $restaurant->parent_district;
        $record->restaurant_alcohol = $restaurant->alcohol;
        $record->restaurant_firework = $restaurant->firework;
        $record->restaurant_name = $restaurant->name;
        $record->restaurant_address = $restaurant->address;
        $record->restaurant_cover_url = $restaurant->cover_url;
        $record->restaurant_latitude = $restaurant->latitude;
        $record->restaurant_longitude = $restaurant->longitude;
        $record->restaurant_own_alcohol = $restaurant->own_alcohol;
        $record->restaurant_cuisine = $restaurant->cuisine;
        $record->restaurant_parking = $restaurant->parking;
        $record->restaurant_extra_services = $restaurant->extra_services;
        $record->restaurant_payment = $restaurant->payment;
        $record->restaurant_special = $restaurant->special;
        $record->restaurant_phone = $restaurant->phone;
        $record->restaurant_commission = $restaurant->commission;
        $record->restaurant_location_sea = 0;
        $record->restaurant_location_river = 0;
        $record->restaurant_location_lake = 0;
        $record->restaurant_location_mount = 0;
        $record->restaurant_location_city = 0;
        $record->restaurant_location_center = 0;
        $record->restaurant_location_outside = 0;
        $location_arr = json_decode($restaurant->location);
        if(count($location_arr) > 0 ){
            foreach ($location_arr as $value) {
                switch ($value) {
                    case 'Около реки':
                        $record->restaurant_location_river = 1;
                        break;
                    case 'Около моря':
                        $record->restaurant_location_sea = 1;
                        break;
                    case 'Около озера':
                        $record->restaurant_location_lake = 1;
                        break;
                    case 'В горах':
                        $record->restaurant_location_mount = 1;
                        break;
                    case 'В городе':
                        $record->restaurant_location_city = 1;
                        break;
                    case 'В центре города':
                        $record->restaurant_location_center = 1;
                        break;
                    case 'За городом':
                        $record->restaurant_location_outside = 1;
                        break;
                }
            }
        }        

        $record->id = $room->id;
        $record->gorko_id = $room->gorko_id;
        $record->restaurant_id = $room->restaurant_id;
        $record->price = $room->price;
        $record->capacity_reception = $room->capacity_reception;
        $record->capacity = $room->capacity;
        $record->type = $room->type;
        $record->rent_only = $room->rent_only;
        $record->banquet_price = $room->banquet_price;
        $record->bright_room = $room->bright_room;
        $record->separate_entrance = $room->separate_entrance;
        $record->type_name = $room->type_name;
        $record->name = $room->name;
        $record->features = $room->features;
        $record->cover_url = $room->cover_url;

        $images = [];
        $thumbs = [];

        foreach ($room->images as $key => $image) {
            $image_arr = [];
            $image_arr['id'] = $image->id;
            $image_arr['sort'] = $image->sort;
            $image_arr['realpath'] = $image->realpath;
            $image_arr['subpath'] = $image->subpath;
            $image_arr['waterpath'] = $image->waterpath;
            $image_arr['timestamp'] = $image->timestamp;
            array_push($images, $image_arr);
            array_push($thumbs, $image_arr);
        }

        $record->images = $images;
        $record->images = $thumbs;

        
        try{
            if(!$isExist){
                $result = $record->insert();
            }
            else{
                $result = $record->update();
            }
        }
        catch(\Exception $e){
            $result = false;
        }
        
        return $result;
    }

    public static function updateDocument($data, $id, $options = [])
    {
        $db = static::getDb();
        $command = $db->createCommand();
        if ($command->exists(static::index(), static::type(), $id)) {
            $options['retry_on_conflict'] = 3;
            $command->update(static::index(), static::type(), $id, $data, $options);
        }

        gc_collect_cycles();
    }
}