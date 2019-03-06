<?php

namespace yii2rails\domain\web\actions;

use yii2rails\domain\base\Action;
use yii2lab\navigation\domain\widgets\Alert;
use Yii;

class DeleteAction extends Action {
	
	public $serviceMethod = 'deleteById';
	
	public function run($id) {
		$method = $this->serviceMethod;
		$this->service->$method($id);
		\App::$domain->navigation->alert->create(['main', 'delete_success'], Alert::TYPE_SUCCESS);
		return $this->redirect(rtrim($this->baseUrl, SL));
	}
}
