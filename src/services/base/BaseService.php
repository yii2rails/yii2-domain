<?php

namespace yii2lab\domain\services\base;

use yii2lab\domain\Domain;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use Yii;
use yii\base\Component as YiiComponent;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\domain\traits\ReadEventTrait;

/**
 * Class BaseService
 *
 * @package yii2lab\domain\services
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
	
	public function access() {
		return [];
	}
	
	public function getRepository($name = null) {
		$name = !empty($name) ? $name : $this->id;
		return $this->domain->repositories->{$name};
	}
	
}