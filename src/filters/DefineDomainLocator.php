<?php

namespace yii2rails\domain\filters;

use Yii;
use App;
use yii\base\InvalidConfigException;
use yii2rails\app\domain\helpers\CacheHelper;
use yii2rails\domain\base\BaseDomainLocator;
use yii2rails\extension\scenario\base\BaseScenario;
use yii2rails\extension\scenario\collections\ScenarioCollection;
use yii2rails\extension\scenario\helpers\ScenarioHelper;

class DefineDomainLocator extends BaseScenario
{
	
	public $filters = [];
	
	public function run()
	{
		App::$domain = new BaseDomainLocator;
		$domains = $this->loadConfig();
		App::$domain->setComponents($domains);
	}
	
	private function loadConfig()
	{
		$definition = '';
		$callback = function () use ($definition) {
			$filterCollection = new ScenarioCollection($this->filters);
			$domains = $filterCollection->runAll([]);
			return $domains;
		};
		$config = CacheHelper::forge(APP . '_domain_config', $callback);
		return $config;
	}
	
}
