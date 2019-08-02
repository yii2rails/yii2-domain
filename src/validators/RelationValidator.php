<?php

namespace yii2rails\domain\validators;

use Yii;
use yii\validators\Validator;
use yii\web\NotFoundHttpException;
use yii2bundle\lang\domain\helpers\LangHelper;
use yii2rails\domain\interfaces\services\ReadInterface;
use yii2rails\extension\web\helpers\ControllerHelper;

class RelationValidator extends Validator {

    public $provider;
    public $message = ['main', 'not_found_relation'];

	public function validateAttribute($model, $attribute) {
        $value = $model->{$attribute};
        /** @var ReadInterface $service */
        $service = ControllerHelper::forgeService($this->provider);
        try {
            $service->oneById($value);
        } catch (NotFoundHttpException $e) {
            $message = LangHelper::extract($this->message);
            $this->addError($model, $attribute, $message, ['attribute' => $attribute]);
        }
	}

}
