<?php

namespace yii2module\account\domain\v2\services;

use yii\base\InvalidConfigException;
use yii2lab\app\domain\helpers\EnvService;
use yii2module\account\domain\v2\entities\JwtProfileEntity;
use yii2module\account\domain\v2\interfaces\services\JwtInterface;
use yii2lab\domain\services\base\BaseService;
use yii2lab\domain\Alias;
use yii2lab\helpers\yii\ArrayHelper;
use yii2module\account\domain\v2\entities\JwtEntity;

/**
 * Class JwtService
 * 
 * @package yii2module\account\domain\v2\services
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 * @property-read \yii2module\account\domain\v2\interfaces\repositories\JwtInterface $repository
 */
class JwtService extends BaseService implements JwtInterface {

    const DEFAULT_PROFILE = 'default';

    private $profiles = null;

    public function init() {
        $this->profiles = EnvService::get('account.jwt.profiles');
    }

    private function getProfile($name) {
        $profile = $this->profiles[$name];
        if(empty($profile)) {
            throw new InvalidConfigException("Profile \"{$name}\" not defined!");
        }
        $profileEntity = new JwtProfileEntity($profile);
        $profileEntity->name = $name;
        $profileEntity->validate();
        return $profileEntity;
    }

    public function sign(JwtEntity $jwtEntity, $profileName = self::DEFAULT_PROFILE) {
        $this->prepEntity($jwtEntity);
        $profileEntity = $this->getProfile($profileName);
        $this->repository->sign($jwtEntity, $profileEntity);
        return $jwtEntity;
    }

    public function encode(JwtEntity $jwtEntity, $profileName = self::DEFAULT_PROFILE) {
        $this->sign($jwtEntity, $profileName);
        return $jwtEntity->token;
    }

    public function decode($token, $profileName = self::DEFAULT_PROFILE) {
        $profileEntity = $this->getProfile($profileName);
        $jwtEntity = $this->repository->decode($token, $profileEntity);
        $jwtEntity->token = $token;
        return $jwtEntity;
    }

    private function prepEntity(JwtEntity $jwtEntity) {
        if(!$jwtEntity->issuer_url) {
            $jwtEntity->issuer_url = EnvService::getUrl(API, 'v1/auth');
        }
        if(!$jwtEntity->subject_url && $jwtEntity->subject_id) {
            $jwtEntity->subject_url = EnvService::getUrl(API, 'v1/user/' . $jwtEntity->subject_id);
        }
    }

}

/**
 * Example
 *
 *
 * 'account' => [
'jwt' => [
'profiles' => [
'default' => [
'key' => 'qwerty123',
'life_time' => \yii2lab\misc\enums\TimeEnum::SECOND_PER_MINUTE * 20,
'allowed_algs' => ['HS256', 'SHA512', 'HS384'],
'default_alg' => 'HS256',
'audience' => ["http://api.core.yii"],
],
],
],
],
 *
 *
 * $jwtEntity = new JwtEntity();
//$jwtEntity->audience = ["http://api.wooppay.yii"];
$jwtEntity->subject_id = \Dii::$domain->account->auth->identity->id;
//$jwtEntity->subject_url = "http://api.core.yii/v1/user/" . $jwtEntity->subject_id;

\Dii::$domain->account->jwt->sign($jwtEntity);
$jwt = $jwtEntity->token;
$decodedEntity = \Dii::$domain->account->jwt->decode($jwt);

if($decodedEntity->toArray() != $jwtEntity->toArray()) {
prr('Not equaled!',1,1);
}
prr($jwtEntity,1,1);
 *
 */