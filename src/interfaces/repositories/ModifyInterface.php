<?php

namespace yii2rails\domain\interfaces\repositories;

use yii2rails\domain\BaseEntity;

interface ModifyInterface extends RepositoryInterface {
	
	/**
	 * @param BaseEntity $entity
	 *
	 * @throws \yii2rails\domain\exceptions\UnprocessableEntityHttpException
	 */
	public function insert(BaseEntity $entity);

	/**
	 * @param BaseEntity $entity
	 *
	 * @throws \yii2rails\domain\exceptions\UnprocessableEntityHttpException
	 */
	public function update(BaseEntity $entity);

    /**
     * @param BaseEntity $entity
     *
     * @throws \yii2rails\domain\exceptions\UnprocessableEntityHttpException
     */
    //public function updateOrInsert(BaseEntity $entity);

	/**
	 * @param BaseEntity $entity
	 *
	 */
	public function delete(BaseEntity $entity);

    public function truncate();

}