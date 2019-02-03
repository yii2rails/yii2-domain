<?php

namespace yii2rails\domain\filters;

use yii2rails\app\domain\filters\config\LoadConfig;
use yii2rails\domain\helpers\ConfigHelper;

class LoadDomainConfig extends LoadConfig {
	
	public $assignTo = '';
	
	protected function normalizeItem($domainId, $data) {
		return ConfigHelper::normalizeItemConfig($domainId, $data);
	}
	
}
