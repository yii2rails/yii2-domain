<?php

namespace yii2rails\domain\services\base;

use yii\base\InvalidArgumentException;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\enums\ActiveMethodEnum;
use yii2rails\domain\events\ServiceMethodEvent;
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
use yii2rails\domain\enums\ScenarioEnum;

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

    public $dataProviderFromSelf = false;

    public function sort() {
        return [];
    }

    public function getDataProvider(Query $query = null) {
        $query = $this->prepareQuery($query, ActiveMethodEnum::READ_ALL);
        //if($this->repository instanceof ReadPaginationInterface) {
        if(!$this->dataProviderFromSelf && method_exists($this->repository, 'getDataProvider')) {
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
        throw new DeprecatedException(_METHOD_);
    }

    public function isExistsById($id) {
        $beforeEvent = $this->beforeAction(self::EVENT_VIEW, ['id' => $id]);
        return $this->repository->isExistsById($id);
    }

    public function isExists($condition) {
        $beforeEvent = $this->beforeAction(self::EVENT_VIEW, ['condition' => $condition]);
        return $this->repository->isExists($condition);
    }

    public function one(Query $query = null) {
        $beforeEvent = $this->beforeAction(self::EVENT_VIEW, ['query' => $query]);
        $query = $this->prepareQuery($query, ActiveMethodEnum::READ_ONE);
        $result = $this->repository->one($query);
        if(empty($result)) {
            throw new NotFoundHttpException(_METHOD_ . ':' . _LINE_);
        }
        $result = $this->afterReadTrigger($result, $query);
        return $this->afterAction(self::EVENT_VIEW, $result, ['query' => $query]);
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
        $beforeEvent = $this->beforeAction(self::EVENT_VIEW, ['id' => $id, 'query' => $query]);
        $query = $this->prepareQuery($query, ActiveMethodEnum::READ_ONE);
        $result = $this->repository->oneById($id, $query);
        if(empty($result)) {
            throw new NotFoundHttpException(__METHOD__ . ':' . __LINE__);
        }
        $result = $this->afterReadTrigger($result, $query);
        return $this->afterAction(self::EVENT_VIEW, $result, ['id' => $id, 'query' => $query]);
    }

    public function count(Query $query = null) {
        $beforeEvent = $this->beforeAction(self::EVENT_INDEX, ['query' => $query]);
        $query = $this->prepareQuery($query, ActiveMethodEnum::READ_COUNT);
        $result = $this->repository->count($query);
        return $this->afterAction(self::EVENT_INDEX, $result, ['query' => $query]);
    }

    public function all(Query $query = null) {
        $beforeEvent = $this->beforeAction(self::EVENT_INDEX, ['query' => $query]);
        $query = $this->prepareQuery($query, ActiveMethodEnum::READ_ALL);
        $result = $this->repository->all($query);
        $result = $this->afterReadTrigger($result, $query);
        return $this->afterAction(self::EVENT_INDEX, $result, ['query' => $query]);
    }

    public function createEntity(BaseEntity $entity) {
        $beforeEvent = $this->beforeAction(self::EVENT_CREATE, ['entity' => $entity]);
        $entity->validate();
        $entity = $this->repository->insert($entity);
        return $this->afterAction(self::EVENT_CREATE, $entity, ['entity' => $entity]);
    }

    public function create($data) {
        $beforeEvent = $this->beforeAction(self::EVENT_CREATE, ['data' => $data]);
        $data = ArrayHelper::toArray($beforeEvent->params['data']);
        /** @var \yii2rails\domain\BaseEntity $entity */
        $entity = $this->domain->factory->entity->create($this->id, $data);
        $this->beforeCreate($entity);
        $entity->validate();
        $entity = $this->repository->insert($entity);
        $this->afterCreate($entity);
        return $this->afterAction(self::EVENT_CREATE, $entity, ['data' => $data]);
    }

    // todo: протестить
    public function update(BaseEntity $entity) {
        $beforeEvent = $this->beforeAction(self::EVENT_UPDATE, ['entity' => $entity]);
        $this->beforeUpdate($entity);
        $data = ArrayHelper::toArray($entity);
        $entity->load($data);
        $entity->validate();
        $this->repository->update($entity);
        $this->afterUpdate($entity);

        return $this->afterAction(self::EVENT_UPDATE, null, ['entity' => $entity]);
    }

    public function updateById($id, $data) {
        $beforeEvent = $this->beforeAction(self::EVENT_UPDATE, ['id' => $id, 'data' => $data]);
        $data = ArrayHelper::toArray($beforeEvent->params['data']);
        $entity = $this->oneById($id);
        $entity->load($data);
        $this->beforeUpdate($entity);
        $entity->validate();
        $this->repository->update($entity);
        $this->afterUpdate($entity);

        return $this->afterAction(self::EVENT_UPDATE, $entity, ['data' => $data]);
    }

    public function deleteById($id) {
        $beforeEvent = $this->beforeAction(self::EVENT_DELETE, ['id' => $id]);
        $entity = $this->oneById($id);
        $this->repository->delete($entity);
        return $this->afterAction(self::EVENT_DELETE, null, ['id' => $id]);
    }

    protected function beforeAction($action, array $params = []) {
        $event = new ServiceMethodEvent($action);
        $event->params = $params;
        $this->trigger($action, $event);
        if(!$event->isValid) {
            throw new ServerErrorHttpException('Service method "' . $action . '" not allow!');
        }
        return $event;
    }

    protected function afterAction($action, $result = null, array $params = []) {
        $event = new ActionEvent($action);
        $event->result = $result;
        $this->trigger($action, $event);
        return $event->result;
    }

    protected function beforeCreate($entity)
    {
        $entity->scenario = ScenarioEnum::SCENARIO_CREATE;
    }

    protected function afterCreate($entity){}

    protected function beforeUpdate($entity)
    {
        $entity->scenario = ScenarioEnum::SCENARIO_UPDATE;
    }

    protected function afterUpdate($entity){}

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