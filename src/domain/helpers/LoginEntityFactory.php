<?php

namespace yii2module\account\domain\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\domain\helpers\ServiceHelper;

class LoginEntityFactory {
	
	public static function forgeLoginEntity($user)
	{
		if(empty($user)) {
			return null;
		}
		$user = ArrayHelper::toArray($user);
		foreach(Yii::$app->account->login->relations as $propName => $serviceName) {
			$user[$propName] = ServiceHelper::oneById($serviceName, $user['login']);
		}
		return self::forgeEntity($user);
	}
	
	protected static function forgeEntity($data) {
		if(empty($data)) {
			return [];
		}
		if(!ArrayHelper::isIndexed($data)) {
			return Yii::$app->account->factory->entity->create('login', $data);
		}
		$result = [];
		foreach($data as $item) {
			$result[] = self::forgeEntity($item);
		}
		return $result;
	}
	
}