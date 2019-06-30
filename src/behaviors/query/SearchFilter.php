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
use yii2rails\extension\common\enums\LogicOperatorEnum;
use yii2rails\extension\common\helpers\StringHelper;
use yii2rails\extension\yii\helpers\ArrayHelper;

class SearchFilter extends Behavior {

    use CallbackTrait;

    public $searchParamName = 'search';
    public $minLength = 3;
    public $fields = [];
    public $virtualFields = [];
    public $concatenator = LogicOperatorEnum::OR;

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
        $concatenator = ArrayHelper::getValue($search, 'concatenator');
        unset($search['concatenator']);
        if(empty($concatenator)) {
            $concatenator = $this->concatenator;
        }
        $condition = [$concatenator];
        foreach($search as $attrKey => $attrValue) {
            $attrValue = trim($attrValue);
            $attrValue = StringHelper::removeDoubleSpace($attrValue);
            if(array_key_exists($attrKey, $this->virtualFields)) {
                foreach ($this->virtualFields[$attrKey] as $virtualFieldKey) {
                    $condition[] = $this->generateLikeConditionItem($virtualFieldKey, $attrValue, $repository);
                }
            } else {
                $condition[] = $this->generateLikeConditionItem($attrKey, $attrValue, $repository);
            }
        }
        return $condition;
    }

    private function securityValue($value) {
        $value = str_replace([
            '"',
            "'",
            '<',
            '>',
            '%',
            ';',
            //'.', 
            ',',
            "\\",
            '/',
            '*',
            '+',
            //'-',
        ], '', $value);
        return $value;
    }

    private function generateLikeConditionItem($attrKey, $attrValue, BaseRepository $repository) {
        $condition = [
            'or',
        ];
        $values = explode(SPC, $attrValue);
        foreach ($values as $value) {
            //$this->validateSearchText($attrValue, $attrKey);
            $attrKey = $repository->alias->encode($attrKey);
            $attrKey = $this->securityValue($attrKey);
            $value = $this->securityValue($value);
            $value = mb_strtolower($value);
            $condition[] = new Expression('lower(cast("' . $attrKey . '" as varchar)) ilike \'%' . $value. '%\'');
        }
        return $condition;
    }

    private function validateSearchText($text, $attribute) {
        $text = trim($text);
        if(!in_array($attribute, $this->fields)) {
            throw new BadRequestHttpException('Attribute "' . $attribute . '" not for serach!');
        }
        /*if(empty($text) || mb_strlen($text) < $this->minLength) {
            throw new BadRequestHttpException(\Yii::t('yii', '{attribute} should contain at least {min, number} {min, plural, one{character} other{characters}}.', [
                'attribute' => $attribute,
                'min' => $this->minLength,
            ]));
        }*/
    }
}
