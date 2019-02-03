<?php

namespace yii2rails\domain\web;

use yii\web\Controller as YiiController;
use yii2rails\extension\web\helpers\ControllerHelper;

class Controller extends YiiController {
	
	public $service = null;
	
	public function init() {
		parent::init();
		$this->service = ControllerHelper::forgeService($this->service);
	}
	
}
