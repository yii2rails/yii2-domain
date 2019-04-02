<?php

namespace yii2rails\domain\traits\repository;

use yii\web\NotFoundHttpException;
use yii2rails\domain\BaseEntity;

trait UpdateOrInsertTrait {

    public function updateOrInsert(BaseEntity $entity) {
        try {
            $this->update($entity);
        } catch (NotFoundHttpException $e) {
            $this->insert($entity);
        }
    }
	
}