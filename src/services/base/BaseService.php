<?php

namespace yii2rails\domain\services\base;

use yii2rails\domain\Domain;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use Yii;
use yii\base\Component as YiiComponent;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\domain\traits\ReadEventTrait;

/**
 * Class BaseService
 *
 * @package yii2rails\domain\services
 *
 * @property BaseRepository $repository
 * @property Domain $domain
 */
class BaseService extends YiiComponent {
	
	use ReadEventTrait;
	
	/**
	 * @deprecated
	 */
	const EVENT_BEFORE_ACTION = 'beforeAction';
	
	/**
	 * @deprecated
	 */
	const EVENT_AFTER_ACTION = 'afterAction';
	
	public $id;
	
	/** @var Domain */
	public $domain;

	public function getRepository($name = null) {
		$name = !empty($name) ? $name : $this->id;
		return $this->domain->repositories->{$name};
	}
	
}