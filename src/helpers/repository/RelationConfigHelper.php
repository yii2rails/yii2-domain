<?php

namespace yii2rails\domain\helpers\repository;

use yii2rails\domain\entities\relation\RelationEntity;
use yii2rails\domain\helpers\Helper;

class RelationConfigHelper {
	
	/**
	 * @param $domain
	 * @param $id
	 *
	 * @return RelationEntity[]
	 */
	public static function getRelationsConfig(string $domain, string $repositoryId) : array {
		$repositoryEntity = \App::$domain->get($domain)->repositories->get($repositoryId);
		$relations =  $repositoryEntity->relations();
		$relations = self::normalizeConfig($relations);
		$relationsCollection = Helper::forgeEntity($relations, RelationEntity::class, true, true);
		return $relationsCollection;
	}
	
	private static function normalizeConfig(array $relations) : array {
		foreach($relations as &$relation) {
			if(!empty($relation['via']['this'])) {
				$relation['via']['self'] = $relation['via']['this'];
				unset($relation['via']['this']);
			}
		}
		return $relations;
	}
	
}
