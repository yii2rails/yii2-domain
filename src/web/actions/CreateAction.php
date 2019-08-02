<?php

namespace yii2rails\domain\web\actions;

use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2bundle\navigation\domain\widgets\Alert;
use Yii;
use yii2rails\domain\base\Action;

class CreateAction extends Action {
	
	public $serviceMethod = 'create';
	public $redirect;
	
	public function run() {
		$this->view->title = Yii::t('main', 'create_title');
		$direction = rtrim($this->baseUrl, SL);
		$model = $this->createForm();

		if(Yii::$app->request->isPost && !$model->hasErrors()) {
			try{
				$this->runServiceMethod($model->toArray());
                if (isset($this->redirect) && !empty($this->redirect)) {
                    $direction = rtrim($this->baseUrl, SL) . $this->redirect;
                }
				\App::$domain->navigation->alert->create(['main', 'create_success'], Alert::TYPE_SUCCESS);
				return $this->redirect($direction);
			} catch (UnprocessableEntityHttpException $e){
				$model->addErrorsFromException($e);
			}
		}
		return $this->render($this->render, compact('model'));
	}
}
