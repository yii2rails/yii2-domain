<?php

namespace yii2rails\domain\services\base;

use yii\base\InvalidArgumentException;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\enums\ActiveMethodEnum;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\domain\interfaces\repositories\ReadExistsInterface;
use yii2rails\domain\interfaces\services\CrudInterface;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii\base\ActionEvent;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii2rails\domain\data\ActiveDataProvider;
use yii2rails\extension\activeRecord\helpers\SearchHelper;
use yii2rails\extension\common\exceptions\DeprecatedException;

/**
 * Class ActiveBaseService
 *
 * @package yii2rails\domain\services
 *
 * @property-read \yii2rails\domain\interfaces\repositories\CrudInterface|ReadExistsInterface $repository
 */
class BaseActiveService extends BaseService implements CrudInterface {
	
	const EVENT_INDEX = 'index';
	const EVENT_CREATE = 'create';
	const EVENT_VIEW = 'view';
	const EVENT_UPDATE = 'update';
	const EVENT_DELETE = 'delete';

	public function sort() {
		return [];
	}
	
	public function getDataProvider(Query $query = null) {
		$query = $this->prepareQuery($query, ActiveMethodEnum::READ_ALL);
		//if($this->repository instanceof ReadPaginationInterface) {
		if(method_exists($this->repository, 'getDataProvider')) {
			$dataProvider = $this->repository->getDataProvider($query);
		}
		if(empty($dataProvider)) {
			$dataProvider = new ActiveDataProvider([
				'query' => $query,
				'service' => $this,
			]);
		}
		$dataProvider->models = $this->afterReadTrigger($dataProvider->models, $query);
		$dataProvider->sort = $this->sort();
		return $dataProvider;
	}
	
	protected function addUserId(BaseEntity $entity) {
		throw new DeprecatedException(__METHOD__);
	}
	
	public function isExistsById($id) {
		$this->beforeAction(self::EVENT_VIEW);
		return $this->repository->isExistsById($id);
	}
	
	public function isExists($condition) {
		$this->beforeAction(self::EVENT_VIEW);
		return $this->repository->isExists($condition);
	}
	
	public function one(Query $query = null) {
		$this->beforeAction(self::EVENT_VIEW);
		$query = $this->prepareQuery($query, ActiveMethodEnum::READ_ONE);
		$result = $this->repository->one($query);
		if(empty($result)) {
			throw new NotFoundHttpException(__METHOD__ . ':' . __LINE__);
		}
		$result = $this->afterReadTrigger($result, $query);
		return $this->afterAction(self::EVENT_VIEW, $result);
	}
	
	/**
	 * @param            $id
	 * @param Query|null $query
	 *
	 * @return \yii2rails\domain\BaseEntity $entity
	 * @throws NotFoundHttpException
	 * @throws \yii\web\ServerErrorHttpException
	 */
	public function oneById($id, Query $query = null) {
		if(empty($id)) {
			throw new InvalidArgumentException('ID can not be empty in ' . __METHOD__ . ' ' . static::class);
		}
		$this->beforeAction(self::EVENT_VIEW);
		$query = $this->prepareQuery($query, ActiveMethodEnum::READ_ONE);
		$result = $this->repository->oneById($id, $query);
		if(empty($result)) {
			throw new NotFoundHttpException(__METHOD__ . ':' . __LINE__);
		}
		$result = $this->afterReadTrigger($result, $query);
		return $this->afterAction(self::EVENT_VIEW, $result);
	}
	
	public function count(Query $query = null) {
		$this->beforeAction(self::EVENT_INDEX);
		$query = $this->prepareQuery($query, ActiveMethodEnum::READ_COUNT);
		$result = $this->repository->count($query);
		return $this->afterAction(self::EVENT_INDEX, $result);
	}
	
	public function all(Query $query = null) {
		$this->beforeAction(self::EVENT_INDEX);
		$query = $this->prepareQuery($query, ActiveMethodEnum::READ_ALL);
		$result = $this->repository->all($query);
		$result = $this->afterReadTrigger($result, $query);
		return $this->afterAction(self::EVENT_INDEX, $result);
	}
	
	public function create($data) {
		$this->beforeAction(self::EVENT_CREATE);
		$data = ArrayHelper::toArray($data);
		/** @var \yii2rails\domain\BaseEntity $entity */
		$entity = $this->domain->factory->entity->create($this->id, $data);
		
		$entity->validate();
		$entity = $this->repository->insert($entity);
		return $this->afterAction(self::EVENT_CREATE, $entity);
	}
	
	// todo: протестить
	public function update(BaseEntity $entity) {
		$this->beforeAction(self::EVENT_UPDATE);
		$data = ArrayHelper::toArray($entity);
		$entity->validate();
		$this->repository->update($entity);
		return $this->afterAction(self::EVENT_UPDATE);
	}
	
	public function updateById($id, $data) {
		$this->beforeAction(self::EVENT_UPDATE);
		$data = ArrayHelper::toArray($data);
		$entity = $this->oneById($id);
		$entity->load($data);
		$entity->validate();
		$this->repository->update($entity);
		return $this->afterAction(self::EVENT_UPDATE, $entity);
	}
	
	public function deleteById($id) {
		$this->beforeAction(self::EVENT_DELETE);
		$entity = $this->oneById($id);
		$this->repository->delete($entity);
		return $this->afterAction(self::EVENT_DELETE);
	}

	protected function beforeAction($action) {
		$event = new ActionEvent($action);
		$this->trigger($action, $event);
		if(!$event->isValid) {
			throw new ServerErrorHttpException('Service method "' . $action . '" not allow!');
		}
	}
	
	protected function afterAction($action, $result = null) {
		$event = new ActionEvent($action);
		$event->result = $result;
		$this->trigger($action, $event);
		return $event->result;
	}

	/*private function checkAccess($action, $accessList = null, $param = null) {
		if(!$accessList) {
			return true;
		}
		foreach($accessList as $access) {
			$this->checkAccessRule($action, $access, $param);
		}
		return true;
	}

	private function checkAccessRule($action, $access, $param = null) {
		$access['only'] = !empty($access['only']) ? ArrayHelper::toArray($access['only']) : null;
		$isIntersectAction = empty($access['only']) || in_array($action, $access['only']);
		if($isIntersectAction) {
			\App::$domain->rbac->manager->can($access['roles'], $param);
		}
	}*/

}