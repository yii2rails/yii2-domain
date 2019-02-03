<?php

namespace yii2rails\domain\helpers\types;

class ArrayType extends BaseType {
	
	protected function _isValid($value, $params = null) {
		return is_array($value);
	}
	
	public function normalizeValue($value, $params = null) {
		return $value;
	}
}