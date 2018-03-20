<?php

namespace yii2module\account\module\helpers;

use Yii;
use yii2lab\extension\menu\interfaces\MenuInterface;
use yii2lab\extension\menu\helpers\MenuHelper;
use yii2lab\helpers\yii\Html;
use yii2module\profile\widget\Avatar;

class Menu implements MenuInterface {
	
	public function toArray() {
		return self::menu(null);
	}
	
	public static function menu($items) {
		return $menu = [
			'label' => self::getTitle(),
			'module' => 'user',
			'encode' => false,
			'items' => self::getItems($items),
		];
	}
	
	public static function getItems($items = null) {
		if(!empty($items)) {
			return $items;
		}
		if(Yii::$app->user->isGuest) {
			return self::getGuestMenu();
		} else {
			return self::getUserMenu();
		}
	}
	
	private static function getTitle() {
		if(Yii::$app->user->isGuest) {
			return Html::fa('user') . NBSP . Yii::t('account/auth', 'title');
		} else {
			return Avatar::widget() . NBSP . Yii::$app->user->identity->username;
		}
	}
	
	private static function getGuestMenu()
	{
		return [
			[
				'label' => ['account/auth', 'login_action'],
				'url' => Yii::$app->user->loginUrl,
			],
			[
				'label' => ['account/registration', 'title'],
				'url' => 'user/registration',
			],
			[
				'label' => ['account/password', 'title'],
				'url' => 'user/password',
			],
		];
	}
	
	private static function getUserMenu()
	{
		return [
			'yii2module\profile\module\v1\helpers\Menu',
			//MenuHelper::DIVIDER,
			[
				'label' => ['account/security', 'title'],
				'url' => 'user/security',
			],
			[
				'label' => ['account/auth', 'logout_action'],
				'url' => 'user/auth/logout',
				'linkOptions' => ['data-method' => 'post'],
			],
		];
	}

}
