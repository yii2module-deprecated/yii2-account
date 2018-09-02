<?php

namespace yii2module\account\domain\v2\repositories\jwt;

use yii\helpers\ArrayHelper;
use yii2module\account\domain\v2\entities\JwtEntity;
use yii2module\account\domain\v2\entities\JwtProfileEntity;
use yii2module\account\domain\v2\interfaces\repositories\JwtInterface;
use yii2lab\domain\repositories\BaseRepository;
use Firebase\JWT\JWT;

/**
 * Class JwtRepository
 * 
 * @package yii2module\account\domain\v2\repositories\jwt
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 */
class JwtRepository extends BaseRepository implements JwtInterface {

    public function fieldAlias() {
        return [
            'issuer_url' => 'iss',
            'subject_url' => 'sub',
            'audience' => 'aud',
            'expire_at' => 'exp',
            'begin_at' => 'nbf',
        ];
    }

    public function sign(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity) {

        if($profileEntity->audience) {
            $jwtEntity->audience = ArrayHelper::merge($jwtEntity->audience, $profileEntity->audience);
        }
        if($profileEntity->life_time) {
            $jwtEntity->expire_at = TIMESTAMP + $profileEntity->life_time;
        }

        $data = $this->entityToToken($jwtEntity);
        $jwtEntity->token = JWT::encode($data, $profileEntity->key);
    }

    public function encode(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity) {
        $this->sign($jwtEntity, $profileEntity);
        return $jwtEntity->token;
    }

    public function decode($token, JwtProfileEntity $profileEntity) {
        $decoded = JWT::decode($token, $profileEntity->key, $profileEntity->allowed_algs);
        $jwtEntity = $this->tokenToEntity($decoded);
        return $jwtEntity;
    }

    private function tokenToEntity($decoded) {
        $decoded = ArrayHelper::toArray($decoded);
        $data = $this->alias->decode($decoded);
        $jwtEntity = new JwtEntity($data);
        return $jwtEntity;
    }

    private function entityToToken(JwtEntity $jwtEntity) {
        $data = $jwtEntity->toArray();
        $data = array_filter($data, function ($value) {return $value !== null;});
        $data = $this->alias->encode($data);
        return $data;
    }

}
