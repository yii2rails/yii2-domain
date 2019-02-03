<?php

namespace yii2rails\domain\entities\relation;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\enums\RelationEnum;

/**
 * Class RelationEntity
 *
 * @package yii2rails\domain\entities\relation
 *
 * @property $type
 * @property $field
 * @property ForeignEntity $foreign
 * @property ForeignViaEntity $via
 */
class RelationEntity extends BaseEntity {
	
	protected $type;
	protected $field;
	protected $foreign;
	protected $via;
	
	public function fieldType() {
		return [
			'foreign' => ForeignEntity::class,
			'via' => ForeignViaEntity::class,
		];
	}
	
	public function rules() {
		return [
			[['type'], 'required'],
			[['type'], 'in', 'range' => RelationEnum::values()],
		];
	}
	
}
