<?php

namespace aminkt\yii2\mongodb\validators;

use yii\validators\Validator;

/**
 * Class EmbedDocValidator
 * Validate embeded documents in mongodb collections.
 *
 * @package aminkt\yii2\mongodb\validators
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
class EmbedDocValidator extends Validator
{
    public $scenario;
    public $model;

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $attr = $model->{$attribute};
        if (is_array($attr)) {
            $checkModel = new $this->model;
            if($this->scenario){
                $checkModel->scenario = $this->scenario;
            }
            $checkModel->attributes = $attr;
            if (!$checkModel->validate()) {
                foreach ($checkModel->getErrors() as $errorAttr) {
                    foreach ($errorAttr as $value) {
                        $this->addError($model, $attribute, $value);
                    }
                }
            }
        } else {
            $this->addError($model, $attribute, 'should be an array');
        }
    }
}