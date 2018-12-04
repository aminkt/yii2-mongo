<?php

namespace aminkt\yii2\mongodb\behaviors;

use Yii;
use yii\base\Behavior;
use aminkt\yii2\mongodb\models\ActiveRecord;
use yii\base\ErrorException;

/**
 * Class MongoObjectBehavior
 * Convert mongo ids from string to mongo object id.
 *
 * @package aminkt\yii2\mongodb
 */
class MongoObjectBehavior extends Behavior
{
    public $list = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT=> 'prepareMongoObject',
            ActiveRecord::EVENT_BEFORE_UPDATE=> 'prepareMongoObject',
        ];
    }

    public function PrepareMongoObject($event)
    {
        foreach ( $this->list as $property ) {
            if(!$event->sender->hasProperty($property)){
                throw new ErrorException('Please define property in model class.');
            }
            if(!empty($event->sender->$property)){
                if(is_array($event->sender->$property) ) {
                    foreach ( $event->sender->$property as $item ) {
                        $temp[]  = is_string($item) ? new \MongoDB\BSON\ObjectID($item) : $item;
                    }
                    $event->sender->$property = $temp;

                }else{
                    $event->sender->$property  = is_string($event->sender->$property) ? new \MongoDB\BSON\ObjectID($event->sender->$property) : $event->sender->$property;
                }
            }
        }
        return $event->isValid;
    }
}