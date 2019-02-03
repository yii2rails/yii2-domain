<?php

namespace yii2rails\domain\filters;

use Yii;
use App;
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
		/*$config = $this->getData();
		$domains = $config['params']['domains'];
		unset($config['params']['domains']);*/
		$domains = $this->loadConfig();
		//prr($domains,1,1);
		$this->createDomainLocator($domains);
		//$config = DomainLangHelper::setDomainTranslationConfig($config, $domains);
		//$this->setData($config);
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
	
	private function createDomainLocator($domains)
	{
		$domain = new BaseDomainLocator;
		/*if(class_exists(DomainLocator::class)) {
			$domain = new DomainLocator;
		} else {
			$domain = new BaseDomainLocator;
		}*/
        $domain->setComponents($domains);
        App::$domain = $domain;
		if(property_exists(Yii::class, 'domain')) {
            Yii::$domain = $domain;
        }
	}
	
}
