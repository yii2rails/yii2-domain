<?php

namespace yii2rails\domain\values;

class StringValue extends BaseValue {
	
	public function isValid($value) {
		return is_string($value);
	}
	
}
