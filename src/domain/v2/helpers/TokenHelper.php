<?php

namespace yii2module\account\domain\v2\helpers;

use Yii;
use yii\base\InvalidArgumentException;
use yii2lab\extension\yii\helpers\ArrayHelper;
use yii2module\account\domain\v2\filters\token\BaseTokenFilter;

class TokenHelper {

    public static function authByToken($SourceToken, $types = []) {
	    $tokenArray = self::splitToken($SourceToken);
	    $type = $tokenArray['type'];
	    $token = $tokenArray['token'];
	    $type = self::prepareType($type, $types);
	    $definition = $types[$type];
        $loginEntity = self::runAuthFilter($definition, $token);
        return $loginEntity;
    }
	
    private static function prepareType($type, $types) {
	    if(empty($types[$type])) {
		    $type = ArrayHelper::firstKey($types);
	    }
	    return $type;
    }
    
	private static function runAuthFilter($definition, $token) {
		/** @var BaseTokenFilter $filter */
		$filter = Yii::createObject($definition);
		$loginEntity = $filter->auth($token);
		return $loginEntity;
	}

    private static function splitToken($token) {
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
            $result['type'] = null;
            $result['token'] = $tokenSegments[0];
        } elseif(count($tokenSegments) == 2) {
            $result['type'] = strtolower($tokenSegments[0]);
            $result['token'] = $tokenSegments[1];
        }
        return $result;
    }

}
