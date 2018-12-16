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

    /**
     * Converet string mongo ids to MongoId objects.
     *
     * @param $event
     *
     * @return mixed
     *
     * @see \aminkt\yii2\mongodb\behaviors\MongoObjectBehavior::convertToMongoObjectId()
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\base\ErrorException
     */
    public function PrepareMongoObject($event)
    {
        foreach ( $this->list as $property ) {
            if(!$event->sender->hasProperty($property)){
                throw new ErrorException('Please define property in model class.');
            }
            if(!empty($event->sender->$property)){
                if(is_array($event->sender->$property) ) {
                    // Empty last temps for every property
                    $temp = [];
                    foreach ( $event->sender->$property as $item ) {
                        $temp[]  = self::convertToMongoObjectId($item);
                    }
                    $event->sender->$property = $temp;

                }else{
                    $event->sender->$property  = self::convertToMongoObjectId($event->sender->$property);
                }
            }
        }
        return $event->isValid;
    }

    /**
     * Convert mongo object id if is string to mongoObject id or not return itself.
     *
     * @param $value
     *
     * @return \MongoDB\BSON\ObjectID
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function convertToMongoObjectId($value) {
        return is_string($value) ? new \MongoDB\BSON\ObjectID($value) : $value;
    }
}