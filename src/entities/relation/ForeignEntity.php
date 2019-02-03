<?php

namespace yii2rails\domain\entities\relation;

/**
 * Class ForeignEntity
 *
 * @package yii2rails\domain\entities\relation
 *
 * @property $field
 * @property $value
 */
class ForeignEntity extends BaseForeignEntity {
	
	protected $field = 'id';
	protected $value;
	
}