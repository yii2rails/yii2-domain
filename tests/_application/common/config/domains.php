<?php

use yii\helpers\ArrayHelper;
use yii2tool\test\helpers\TestHelper;

$config = [
    'lang' => 'yii2bundle\lang\domain\Domain',
    'rbac' => 'yii2bundle\rbac\domain\Domain',
];

$baseConfig = TestHelper::loadConfig('common/config/domains.php');
return ArrayHelper::merge($baseConfig, $config);
