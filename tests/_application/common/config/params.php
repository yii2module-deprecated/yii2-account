<?php

use yii\helpers\ArrayHelper;
use yii2lab\test\helpers\TestHelper;

$config = [
	'MaintenanceMode' => false,
	'AcquiringTest' => true,
];

$baseConfig = TestHelper::loadConfig('common/config/params.php');
return ArrayHelper::merge($baseConfig, $config);
