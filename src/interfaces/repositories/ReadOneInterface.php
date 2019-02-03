<?php

namespace yii2rails\domain\interfaces\repositories;

use yii2rails\domain\data\Query;

interface ReadOneInterface extends RepositoryInterface {
	
	/*
	 * @param Query|null $query
	 *
	 * @return \yii2rails\domain\BaseEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	//public function one(Query $query = null);
	
	/**
	 * @param            $id
	 * @param Query|null $query
	 *
	 * @return \yii2rails\domain\BaseEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function oneById($id, Query $query = null);
	
}