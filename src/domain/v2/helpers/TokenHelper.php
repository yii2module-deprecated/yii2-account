<?php

namespace yii2module\account\domain\v2\helpers;

use Yii;
use yii\base\InvalidArgumentException;
use yii2lab\extension\common\helpers\StringHelper;
use yii2lab\extension\yii\helpers\ArrayHelper;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\filters\token\BaseTokenFilter;

class TokenHelper {
	
	public static function login($body, $ip, $types = []) {
		$type = ArrayHelper::getValue($body, 'tokenType');
		$type = self::prepareType($type, $types);
		//$type = !empty($type) ? $type : ArrayHelper::firstKey($this->tokenAuthMethods);
		$definitionFilter = ArrayHelper::getValue($types, $type);
		/*if(!$definitionFilter) {
			$error = new ErrorCollection();
			$error->add('tokenType', 'account/auth', 'token_type_not_found');
			throw new UnprocessableEntityHttpException($error);
		}*/
		/** @var BaseTokenFilter $filterInstance */
		$filterInstance = Yii::createObject($definitionFilter);
		$filterInstance->type = $type;
		$loginEntity = $filterInstance->login($body, $ip);
		return $loginEntity;
	}
	
    public static function authByToken($SourceToken, $types = []) {
	    $tokenArray = self::splitToken($SourceToken);
	    $type = $tokenArray['type'];
	    $token = $tokenArray['token'];
	    $type = self::prepareType($type, $types);
	    $definition = $types[$type];
        $loginEntity = self::runAuthFilter($definition, $token);
        if(!$loginEntity instanceof LoginEntity) {
	        return null;
        }
	    AuthHelper::setToken($token);
        return $loginEntity;
    }
	
    private static function prepareType($type, $types) {
	    if(empty($type)) {
		    $type = ArrayHelper::firstKey($types);
	    } elseif(empty($types[$type])) {
	    	throw new InvalidArgumentException(Yii::t('account/auth', 'token_type_not_found'));
	    }
	    return $type;
    }
    
	private static function runAuthFilter($definition, $token) {
		/** @var BaseTokenFilter $filter */
		$filter = Yii::createObject($definition);
		$loginEntity = $filter->authByToken($token);
		return $loginEntity;
	}

    public static function splitToken($token) {
        $token = trim($token);
        if(empty($token)) {
            throw new InvalidArgumentException('Empty token');
        }
	    $token = trim($token);
	    $token = StringHelper::removeDoubleSpace($token);
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
