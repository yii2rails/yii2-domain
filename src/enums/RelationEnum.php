<?php

namespace yii2rails\domain\enums;

use yii2rails\extension\enum\base\BaseEnum;

class RelationEnum extends BaseEnum {
	
	const ONE = 'one';
	const MANY = 'many';
	const MANY_TO_MANY = 'many-to-many';
	
}
