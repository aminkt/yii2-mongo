<?php

namespace aminkt\yii2\mongodb\behaviors;

use yii\base\Behavior;
use yii\web\ServerErrorHttpException;

/**
 * Class SoftDeleteBehavior
 * Put current record to trash status.
 *
 * @package aminkt\yii2\base\behaviors
 */
class SoftDeleteBehavior extends Behavior
{
    public $deletedAttribute = 'is_trashed';

    public function events()
    {
        return [
            \yii\mongodb\ActiveRecord::EVENT_BEFORE_INSERT => 'initSoftDelete',
            \yii\mongodb\ActiveRecord::EVENT_BEFORE_DELETE => 'softDelete',
        ];
    }

    /**
     * Soft delete action.
     *
     * @param $event
     *
     * @return bool
     *
     * @throws ServerErrorHttpException
     */
    public function softDelete($event)
    {
        $attr = $this->deletedAttribute;
        $this->owner->$attr = true;
        if ($this->owner->save()) {
            $event->isValid = false;
            $this->owner->trigger(\yii\mongodb\ActiveRecord::EVENT_AFTER_DELETE);
            return true;
        }
        throw new Exception('Failed to delete the object for unknown reason.');
    }

    /**
     * Initial soft delete for system creation.
     *
     * @param $event
     *
     * @return bool
     */
    public function initSoftDelete($event)
    {
        $attr = $this->deletedAttribute;
        $event->sender->$attr = false;
        return true;
    }
}
