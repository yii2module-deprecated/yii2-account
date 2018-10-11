<?php

namespace yii2module\account\domain\v2\helpers;

use Yii;
use yii\base\InvalidArgumentException;
use yii2lab\extension\yii\helpers\ArrayHelper;
use yii2module\account\domain\v2\filters\token\BaseTokenFilter;

class TokenHelper {

	const KEY = 'authToken';
    const DEFAULT_TOKEN_TYPE = 'default';
	
	private static function runAuthFilter($definition, $token) {
        /** @var BaseTokenFilter $filter */
	    $filter = Yii::createObject($definition);
        $filter->token = $token;
        $filter->run();
        $loginEntity = $filter->getData();
        return $loginEntity;
    }

    public static function authByToken($token, $type = null, $types = []) {
	    $type = !empty($types[$type]) ? $type : ArrayHelper::firstKey($types);
		if(!$type) {
	        $tokenArray = self::splitToken($token);
	        $token = $tokenArray['token'];
	        $type = $tokenArray['type'];
        }
        $className = $types[$type];
        $loginEntity = self::runAuthFilter($className, $token);
        return $loginEntity;
    }

    public static function splitToken($token) {
        $token = trim($token);
        if(empty($token)) {
            throw new InvalidArgumentException('Empty token');
        }
        $tokenSegments = explode(SPC, $token);
        $countSegments = count($tokenSegments);
        $isValid = $countSegments == 1 || $countSegments == 2;
        if(!$isValid) {
            throw new InvalidArgumentException('Invalid token format');
        }
	    $result = [];
        if(count($tokenSegments) == 1) {
            $result['type'] = self::DEFAULT_TOKEN_TYPE;
            $result['token'] = $tokenSegments[0];
        } elseif(count($tokenSegments) == 2) {
            $result['type'] = strtolower($tokenSegments[0]);
            $result['token'] = $tokenSegments[1];
        }
        return $result;
    }

}
