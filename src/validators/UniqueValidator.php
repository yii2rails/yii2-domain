<?php

namespace yii2rails\domain\validators;

use Yii;
use yii\validators\Validator;
use yii\web\NotFoundHttpException;
use yii2bundle\lang\domain\helpers\LangHelper;
use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\services\ReadInterface;
use yii2rails\domain\services\base\BaseActiveService;
use yii2rails\extension\web\helpers\ControllerHelper;

class UniqueValidator extends Validator {

    public $provider;
    public $message = ['main', 'unique_already_exists'];
    public $fields;
    public $primaryKey = 'id';

    public function validateAttribute($model, $attribute) {
        /** @var ReadInterface $service */
        $service = ControllerHelper::forgeService($this->provider);
        $condition = $model->toArray($this->fields);
        $query = new Query;
        $query->andWhere($condition);
        try {
            $entity = $service->one($query);
            $pk = $this->primaryKey;
            if($pk && $entity->{$pk} == $model->{$pk}) {
                return;
            }
            $message = LangHelper::extract($this->message);
            $this->addError($model, $attribute, $message, ['attribute' => $attribute]);
        } catch (NotFoundHttpException $e) {}
	}

}
