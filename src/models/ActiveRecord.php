<?php

namespace aminkt\yii2\mongodb\models;


/**
 * Class ActiveRecord
 * Base class for database models.
 *
 * @package aminkt\yii2\mongodb
 *
 * @property int $id
 */
class ActiveRecord extends \yii\mongodb\ActiveRecord
{
    /**
     * Get mongo id of model
     *
     * @return integer
     */
    public function getId()
    {
        return (string)$this->_id;
    }

    /**
     * Manipulate default model fields
     *
     * @return array the list of fields to be exported. The array keys are the field names, and the array values
     * are the corresponding object property names or PHP callables returning the field values.
     */
    public function fields()
    {
        $fields = parent::fields();
        if ($i = array_search('_id', $fields)) {
            unset($fields[$i]);
        }
        $fields[] = 'id';

        return $fields;
    }
}