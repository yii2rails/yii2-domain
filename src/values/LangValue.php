<?php

namespace yii2rails\domain\values;

use yii2bundle\lang\domain\helpers\LangHelper;

class LangValue extends BaseValue {
	
	protected function _encode($value) {
		if(is_null($value)) {
			return '';
		}
		return LangHelper::extract($value);
	}
	
	public function isValid($value) {
		if(is_null($value)) {
			return true;
		}
		return is_string($value) || is_array($value);
	}
	
}
