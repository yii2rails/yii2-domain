<?php

namespace yii2rails\domain\locators;

use yii2rails\domain\Domain;
use yii2rails\domain\repositories\BaseRepository;

/**
 * @method BaseRepository get($id)
 */
class RepositoryLocator extends \yii\di\ServiceLocator {
	
	/**
	 * @var Domain
	 */
	public $domain;
	
}