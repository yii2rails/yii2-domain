<?php

namespace yii2rails\domain\traits\behavior;

trait AllowOnlyTrait {
	
	public $allowOnly = [];
	
	protected function isAllow($allowOnly = null) {
		$allowOnly = $allowOnly ? $allowOnly : $this->allowOnly;
		$isAllow = \App::$domain->rbac->manager->isAllow($allowOnly);
		return $isAllow;
	}
	
}