<?php

namespace yii2lab\domain\enums;

use yii2lab\extension\enum\base\BaseEnum;

class JoinEnum extends BaseEnum {

    const JOIN = 'JOIN';
    const INNER = 'INNER JOIN';
	const LEFT = 'LEFT JOIN';
	const RIGHT = 'RIGHT JOIN';
	const FULL = 'FULL JOIN';
	const OUTER = 'OUTER JOIN';
	const CROSS = 'CROSS JOIN';

}
