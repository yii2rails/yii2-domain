<?php

namespace yii2rails\domain\interfaces\services;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;

interface ReadOneInterface {
	
	/**
	 * @param $id
	 *
	 * @return boolean
	 */
	public function isExistsById($id);
	
	/**
	 * @param $condition array
	 *
	 * @return boolean
	 */
	public function isExists($condition);
	
	/**
	 * @param Query|null $query
	 *
	 * @return BaseEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function one(Query $query = null);
	
	/**
	 * @param            $id
	 * @param Query|null $query
	 *
	 * @return BaseEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function oneById($id, Query $query = null);
	
}