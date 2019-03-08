<?php

namespace yii2rails\domain\locators;

use yii\base\UnknownPropertyException;
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

    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (UnknownPropertyException $e) {
            $message =
                'Repository "' . $name . '" not defined! ' .
                'You can configure this service in domain config. ' .
                'Guide - https://github.com/yii2rails/yii2-domain/blob/master/guide/ru/exception-repository-not-defined.md';
            throw new UnknownPropertyException($message, 0, $e);
        }
    }
}