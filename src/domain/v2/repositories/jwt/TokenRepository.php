<?php

namespace yii2module\account\domain\v2\repositories\jwt;

use yii2lab\domain\data\Query;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2module\account\domain\v2\interfaces\repositories\TokenInterface;

/**
 * Class TokenRepository
 * 
 * @package yii2module\account\domain\v2\repositories\ar
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 */
class TokenRepository extends BaseRepository implements TokenInterface {

    public function oneByToken($token) {

    }

    public function allByIp($ip) {

    }

    public function allByUserId($userId) {

    }

    public function deleteOneByToken($token) {

    }

    public function deleteAllExpired() {

    }

}
