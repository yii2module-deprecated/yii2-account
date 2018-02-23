<?php

namespace yii2module\account\module\helpers;

use Yii;
use yii2lab\helpers\interfaces\MenuInterface;
use yii2lab\helpers\MenuHelper;
use yii2lab\helpers\yii\Html;
use yii2module\account\domain\v1\entities\LoginEntity;

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
				t('account/auth', 'title'),
			'module' => 'user',
			'encode' => false,
			'items' => $items,
		];
	}
	
	private function getUserMenu()
	{
		/** @var LoginEntity $identity */
		$identity = Yii::$app->user->identity;
		//$balanceEntity = $identity->balance;
		if(is_object($identity->profile)) {
			$avatar = '<img src="'. $identity->profile->avatar_url . '" height="19" />';
		} else {
			$avatar = '';
		}
		//$balance = '(' . Yii::t('account/balance' ,'title') . ': <b>'. floatval($balanceEntity->active) . '</b>)';
		$balance = '';
		$label = $avatar . NBSP . '<small>'. $identity->username . NBSP .	$balance . '</small>';
		return [
			'label' => $label,
			'module' => 'user',
			'encode' => false,
			'items' => $this->getItemList(),
		];
	}

}
