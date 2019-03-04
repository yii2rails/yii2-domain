<?php

namespace yii2rails\domain\behaviors\entity;

use yii\base\Behavior;
use yii\base\ModelEvent;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\values\TimeValue;

class TimeValueFilter extends Behavior {

    public $attributes = [
        'created_at',
        'updated_at',
    ];

    public function events() {
        return [
            BaseEntity::EVENT_BEFORE_VALIDATE => 'prepare',
        ];
    }

    public function prepare(ModelEvent $event) {
        /** @var BaseEntity $entity */
        $entity = $event->sender;
        foreach ($this->attributes as $attribute) {
            if($entity->hasProperty($attribute) && $entity->{$attribute} == null) {
                $timeValue = new TimeValue(time());
                $entity->{$attribute} = $timeValue;
            }
        }
    }

}
