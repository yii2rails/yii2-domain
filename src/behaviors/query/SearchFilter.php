<?php

namespace yii2rails\domain\behaviors\query;

use yii\base\Behavior;
use yii\db\Expression;
use yii\web\BadRequestHttpException;
use yii2rails\domain\data\Query;
use yii2rails\domain\enums\EventEnum;
use yii2rails\domain\events\QueryEvent;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\domain\traits\behavior\CallbackTrait;

class SearchFilter extends Behavior {

    use CallbackTrait;

    public $searchParamName = 'search';
    public $minLength = 3;
    public $fields = [];

    public function events() {
        return [
            EventEnum::EVENT_PREPARE_QUERY => 'prepareQueryEvent',
        ];
    }

    public function prepareQueryEvent(QueryEvent $event) {
        if(!$this->runCallback($event)) {
            $this->prepareQuery($event->query, $event->sender);
        }
    }

    public function prepareQuery(Query $query, BaseRepository $repository) {
        $search = $query->getWhere($this->searchParamName);
        $query->removeWhere($this->searchParamName);
        if($search) {
            $likeCondition = $this->generateLikeCondition($search, $repository);
            $query->andWhere($likeCondition);
        }
        return $query;
	}

    private function generateLikeCondition($search, BaseRepository $repository) {
        $q = Query::forge();
        foreach($search as $key => $value) {
            $this->validateSearchText($value, $key);
            $key = $repository->alias->encode($key);
            $exp = new Expression('lower(' . $key . ') like \'%' . mb_strtolower($value). '%\'');
            $q->orWhere($exp);
        }
        return $q->getParam(Query::WHERE);
    }

    private function validateSearchText($text, $attribute) {
        $text = trim($text);
        if(!in_array($attribute, $this->fields)) {
            throw new BadRequestHttpException('Attribute "' . $attribute . '" not for serach!');
        }
        if(empty($text) || mb_strlen($text) < $this->minLength) {
            throw new BadRequestHttpException(\Yii::t('yii', '{attribute} should contain at least {min, number} {min, plural, one{character} other{characters}}.', [
                'attribute' => $attribute,
                'min' => $this->minLength,
            ]));
        }
    }
}
