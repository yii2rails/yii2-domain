<?php

namespace yii2rails\domain\web\actions;

use yii2rails\domain\base\Action;
use yii2bundle\navigation\domain\widgets\Alert;
use Yii;

class DeleteAction extends Action {
	
	public $serviceMethod = 'deleteById';
	public $redirect;
	
	public function run($id) {
	    $direction = rtrim($this->baseUrl, SL);
		$method = $this->serviceMethod;
		$this->service->$method($id);

		if (isset($this->redirect) && !empty($this->redirect)) {
            $direction = rtrim($this->baseUrl, SL) . $this->redirect;
        } else {
            $direction = $_SERVER['HTTP_REFERER'];
        }

        \App::$domain->navigation->alert->create(['main', 'delete_success'], Alert::TYPE_SUCCESS);
		return $this->redirect($direction);
	}
}
