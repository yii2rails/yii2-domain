<?php

namespace yii2rails\domain\interfaces\services;

use yii2rails\domain\BaseEntity;

interface ModifyInterface {
	
	/**
	 * @param $data array
	 *
	 * @throws \yii2rails\domain\exceptions\UnprocessableEntityHttpException
	 */
	public function create($data);
	
	//public function update(BaseEntity $entity);
	
	/**
	 * @param $id
	 * @param $data|BaseEntity array
	 *
	 * @throws \yii\web\NotFoundHttpException
	 * @throws \yii2rails\domain\exceptions\UnprocessableEntityHttpException
	 */
	
	public function updateById($id, $data);
	
	public function update(BaseEntity $entity);
	
	/**
	 * @param $id
	 *
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function deleteById($id);
	
}