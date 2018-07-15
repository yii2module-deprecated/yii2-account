<?php

namespace yii2module\account\domain\v2\services;

use yii2module\account\domain\v2\interfaces\services\TokenInterface;
use yii2lab\domain\services\base\BaseActiveService;

/**
 * Class TokenService
 * 
 * @package yii2module\account\domain\v2\services
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 * @property-read \yii2module\account\domain\v2\interfaces\repositories\TokenInterface $repository
 */
class TokenService extends BaseActiveService implements TokenInterface {

}
