<?php

namespace yii2rails\domain\enums;

use yii2rails\extension\enum\base\BaseEnum;

/**
 * Сценарии валидации моделей/сущностей
 *
 * @author kamaelkz <kamaelkz@yandex.kz>
 */
class ScenarioEnum extends BaseEnum
{
    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_TRUSTED = 'trusted';
}