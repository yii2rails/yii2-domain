<?php

namespace yii2rails\domain\generator;

use yii\helpers\Inflector;
use yii2rails\extension\code\helpers\CodeHelper;

class MessageGenerator extends BaseGenerator {

	public $name;
	
	public function run() {
		CodeHelper::generatePhpData($this->name, [
			'title' => Inflector::humanize(basename($this->name)),
		]);
	}
	
}
