<?php

namespace yii2rails\domain\events;

use yii\base\Event;

class DataEvent extends Event {
	
	public $request;
	public $result;
	
}
