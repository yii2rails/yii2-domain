<?php

namespace yii2rails\domain\locators;

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
	
}