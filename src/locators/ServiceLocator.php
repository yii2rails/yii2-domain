<?php

namespace yii2rails\domain\locators;

use yii\base\InvalidConfigException;
use yii\base\UnknownPropertyException;
use yii2rails\domain\Domain;
use yii2rails\domain\services\base\BaseService;

/**
 *
 * @method BaseService get($id)
 *
 */
class ServiceLocator extends \yii\di\ServiceLocator {
	
	/**
	 * @var Domain
	 */
	public $domain;

    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (UnknownPropertyException $e) {
            $message =
                'Service "' . $name . '" not defined! ' . PHP_EOL .
                'Guide - https://github.com/yii2rails/yii2-domain/blob/master/guide/ru/exception-service-not-defined.md';
            throw new InvalidConfigException($message, 0, $e);
        }
    }

}