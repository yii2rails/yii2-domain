<?php

namespace yii2rails\domain\events;

use yii\base\Event;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\extension\arrayTools\helpers\Collection;

class MethodEvent extends Event {
	
	/**
	 * @var BaseEntity|Collection|array
	 */
	public $content;
	
	/**
	 * @var Query
	 */
	public $query;
	public $activeMethod;
	
}
