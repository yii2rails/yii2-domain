<?php

namespace yii2rails\domain\helpers\factory;

use yii2rails\domain\Domain;

class ServiceFactoryHelper extends BaseFactoryHelper {
	
	protected static function genClassName($id, $definition, Domain $domain) {
		$class = 'services\\';
		if(!empty($definition) && is_string($definition)) {
			$class .= $definition . '\\';
		}
		$class .=  ucfirst($id) . 'Service';
		return $class;
	}
	
}
