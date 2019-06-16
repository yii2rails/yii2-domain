<?php

namespace yii2rails\domain\filters;

use Yii;
use App;
use yii\base\InvalidConfigException;
use yii2rails\app\domain\helpers\CacheHelper;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\base\BaseDomainLocator;
use yii2rails\extension\develop\helpers\Benchmark;
use yii2rails\extension\scenario\base\BaseScenario;
use yii2rails\extension\scenario\collections\ScenarioCollection;
use yii2rails\extension\scenario\helpers\ScenarioHelper;
use yii2rails\extension\yii\helpers\FileHelper;

class DefineDomainLocator extends BaseScenario
{

    const DOMAIN_CACHE_NAME = APP.'_define_domains.json';
    const DOMAIN_CACHE = ROOT_DIR . '/common/runtime/cache/app/'.self::DOMAIN_CACHE_NAME;

	public $filters = [];
	
	public function run()
	{
        Benchmark::begin('define_domains_' . __METHOD__);
        if(EnvService::get('cache.enableDomainCache') && file_exists(self::DOMAIN_CACHE)) {
            $content = FileHelper::load(self::DOMAIN_CACHE);
            App::$domain = unserialize($content);
        } else {
            App::$domain = new BaseDomainLocator;
            $domains = $this->loadConfig();
            App::$domain->setComponents($domains);
            FileHelper::save(self::DOMAIN_CACHE, serialize(App::$domain));
        }
        Benchmark::end('define_domains_' . __METHOD__);
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
