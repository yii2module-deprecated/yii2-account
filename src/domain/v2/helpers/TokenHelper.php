<?php

namespace yii2module\account\domain\v2\helpers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2lab\extension\registry\helpers\Registry;
use yii2lab\extension\scenario\base\BaseScenario;
use yii2lab\extension\web\enums\HttpHeaderEnum;
use yii2module\account\domain\v2\entities\LoginEntity;

class TokenHelper {

	const KEY = 'authToken';
    const DEFAULT_TOKEN_TYPE = 'default';

	public static $types = [
        self::DEFAULT_TOKEN_TYPE => 'yii2module\account\domain\v2\filters\token\DefaultFilter',
        'tps' => 'yii2module\account\domain\v2\filters\token\DefaultFilter',
        'jwt' => 'yii2module\account\domain\v2\filters\token\JwtFilter',
    ];

	private static function runAuthFilter($className, $token) {
        /** @var BaseScenario $filter */
	    $filter = new $className;
        $filter->token = $token;
        $filter->run();
        $loginEntity = $filter->getData();
        return $loginEntity;
    }

    private static function runAuthFilterForce($token) {
        foreach (self::$types as $itemType => $className) {
            $data = self::runAuthFilter($className, $token);
            if($data) {
                return $data;
            }
        }
    }

    public static function authByToken($token, $type = null) {
        if(!$type) {
            $tokenArray = self::splitToken($token);
            $token = $tokenArray['token'];
            $type = $tokenArray['type'];
        }
        $className = self::$types[$type];
        $loginEntity = self::runAuthFilter($className, $token);
        /*$loginEntity = null;
        try {
            $loginEntity = self::runAuthFilter($className, $token);
        } catch (NotFoundHttpException $e) {
            $loginEntity = self::runAuthFilterForce($token);
        }*/
        return $loginEntity;


        return null;
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
