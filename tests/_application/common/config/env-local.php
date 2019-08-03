<?php

use yii\helpers\ArrayHelper;
use yii2tool\test\helpers\TestHelper;

$config = [

];

$baseConfig = TestHelper::loadConfig('common/config/env-local.php');
return ArrayHelper::merge($baseConfig, $config);
