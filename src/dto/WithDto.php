<?php

namespace yii2rails\domain\dto;

use yii2rails\domain\base\BaseDto;
use yii2rails\domain\data\Query;
use yii2rails\domain\entities\relation\RelationEntity;

class WithDto extends BaseDto {
	
	/**
	 * @var Query
	 */
	public $query;
	public $remain;
	public $remainOfRelation;
	public $relationName;
	
	/**
	 * @var RelationEntity
	 */
	public $relationConfig;
	public $passed;
	public $withParams;
	
}