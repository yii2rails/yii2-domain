<?php

use yii\helpers\ArrayHelper;
use yii2tool\test\helpers\TestHelper;

$config = [

];

$baseConfig = TestHelper::loadConfig('common/config/main.php');
return ArrayHelper::merge($baseConfig, $config);
