<?php

namespace yii2rails\domain\helpers;

use ReflectionException;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii2rails\domain\Domain;
use yii2rails\extension\common\helpers\ClassHelper;
use yii2rails\extension\common\helpers\Helper;

class ConfigHelper {
	
	public static function normalizeItemConfig($domainId, $data) {
		$data = ClassHelper::normalizeComponentConfig($data);
		$data = Helper::isEnabledComponent($data);
		if(empty($data)) {
			return null;
		}
		$data = self::normalizeSubItems($domainId, $data);
		if(!is_numeric($domainId)) {
			$data['id'] = $domainId;
		}
		if(!empty($data['class'])) {
            $isDomainClass = is_subclass_of($data['class'], Domain::class, true);
            if(!$isDomainClass) {
                throw new InvalidArgumentException('Domain ID: "' . $domainId . '". Class "' . $data['class'] . '" not instance of "' . Domain::class . '"!');
            }
			try {
				$domainInstance = Yii::createObject($data['class']);
			} catch(ReflectionException $e) {
				return null;
			}
			$domainConfig = $domainInstance->config();
			if(!empty($domainConfig)) {
				$domainConfig = self::normalizeSubItems($domainId, $domainConfig);
				$data = ArrayHelper::merge($domainConfig, $data);
			}
		}
		return $data;
	}
	
	protected static function normalizeSubItems($domainId, $config) {
		if(!empty($config['services'])) {
			$config['services'] = self::genConfigs($config['services']);
		}
		if(!empty($config['repositories'])) {
			$config['repositories'] = self::genConfigs($config['repositories']);
		}
		return $config;
	}
	
	protected static function genConfigs($components) {
		$configNew = [];
		foreach($components as $id => $config) {
			if(is_integer($id)) {
				$id = $config;
				$config = [];
			}
			$configNew[$id] = $config;
		}
		return $configNew;
	}
	
}