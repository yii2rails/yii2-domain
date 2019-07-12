<?php

namespace yii2rails\domain\events;

use yii\base\ActionEvent;
use yii\base\Event;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\extension\arrayTools\helpers\Collection;

class ServiceMethodEvent extends ActionEvent {

	public $params = [];

}
