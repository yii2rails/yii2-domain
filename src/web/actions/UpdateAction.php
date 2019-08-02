<?php

namespace yii2rails\domain\web\actions;

use yii\base\Model;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2bundle\navigation\domain\widgets\Alert;
use Yii;
use yii2rails\domain\base\Action;

class UpdateAction extends Action {
	
	public $serviceMethod = 'updateById';
	public $serviceMethodOne = 'oneById';
	
	public function run($id) {
		$this->view->title = Yii::t('main', 'update_title');
		$methodOne = $this->serviceMethodOne;
        /** @var BaseEntity $entity */
		$entity = $this->service->$methodOne($id);
		/** @var Model $model */
		$model = $this->createForm();
        if(Yii::$app->request->isPost) {
            if(!$model->hasErrors()) {
                try{
                    //$onlyAttributes = $model->attributes();
                    //$entity->load($model->toArray(), $onlyAttributes);
                    $method = $this->serviceMethod;
                    $this->service->$method($id, $model->toArray());
                    //$this->service->$method($id, $entity->toArray($onlyAttributes));
                    \App::$domain->navigation->alert->create(['main', 'update_success'], Alert::TYPE_SUCCESS);
                    return $this->redirect(['/' . $this->baseUrl . 'view', 'id' => $id]);
                } catch (UnprocessableEntityHttpException $e){
                    $model->addErrorsFromException($e);
                }
            }
        } else {
            Yii::configure($model, $entity->toArray($model->attributes()));
        }
		return $this->render($this->render, compact('model'));
	}
}
