<?php

namespace yii2module\account\module\helpers;

use Yii;
use yii2lab\extension\menu\interfaces\MenuInterface;
use yii2lab\extension\menu\helpers\MenuHelper;
use yii2lab\helpers\yii\Html;
use yii2module\profile\widget\Avatar;

class Menu implements MenuInterface {
	
	public function toArray() {
		if(Yii::$app->user->isGuest) {
			return $this->getGuestMenu();
		} else {
			return $this->getUserMenu();
		}
	}
	
	private function getItemList() {
		return [
			[
				'label' => ['profile/main','my_profile'],
				'url' => 'profile/person',
				'module' => 'profile',
				'domain' => 'profile',
				'access' => ['@'],
			],
            MenuHelper::DIVIDER,
			[
				'label' => ['account/auth', 'logout_action'],
				'url' => 'user/auth/logout',
				'linkOptions' => ['data-method' => 'post'],
			],
		];
	}
	
	private function getGuestMenu()
	{
		$items = [];
		$items[] = ['label' => ['account/auth', 'login_action'], 'url' => Yii::$app->user->loginUrl];
		if(APP == FRONTEND) {
			$items[] = ['label' => ['account/registration', 'title'], 'url' => 'user/registration'];
			$items[] = ['label' => ['account/password', 'title'], 'url' => 'user/password'];
		}
		return [
			'label' => 
				Html::fa('user') . NBSP . 
				Yii::t('account/auth', 'title'),
			'module' => 'user',
			'encode' => false,
			'items' => $items,
		];
	}
	
	private function getUserMenu()
	{
		$label =  Avatar::widget() . NBSP . Yii::$app->user->identity->username;
		return [
			'label' => $label,
			'module' => 'user',
			'encode' => false,
			'items' => $this->getItemList(),
		];
	}

}
