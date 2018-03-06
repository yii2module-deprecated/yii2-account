<?php

namespace yii2module\account\module\helpers;

use Yii;
use yii2lab\domain\data\Query;
use yii2lab\extension\menu\interfaces\MenuInterface;
use yii2lab\extension\menu\helpers\MenuHelper;
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
				Yii::t('account/auth', 'title'),
			'module' => 'user',
			'encode' => false,
			'items' => $items,
		];
	}
	
	private function getUserMenu()
	{
		$label =  $this->getAvatar(). NBSP . '<small>'. Yii::$app->user->identity->username . '</small>';
		return [
			'label' => $label,
			'module' => 'user',
			'encode' => false,
			'items' => $this->getItemList(),
		];
	}

	private function getAvatar() {
		$query = Query::forge();
		$profileRelations = Yii::$app->account->login->relations['profile'];
		if($profileRelations) {
			foreach($profileRelations as $relation) {
				$query->with($relation);
			}
		}
		$profile = Yii::$app->profile->profile->oneById(Yii::$app->user->identity->id, $query);
		if(is_object($profile) && is_object($profile->avatar)) {
			$avatar = '<img src="'. $profile->avatar->url . '" height="19" />';
		} else {
			$avatar = '';
		}
		return $avatar;
	}
}
