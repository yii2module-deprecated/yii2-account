<?php

namespace yii2module\account\domain\v2\repositories\filedb;

use Yii;
use yii\web\UnauthorizedHttpException;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\repositories\ActiveFiledbRepository;

class SecurityRepository extends ActiveFiledbRepository {
	
	public function tableName()
	{
		return 'user';
	}
	
	public function changePassword($password, $newPassword) {
		$userModel = $this->getUserModel($password);
		$userModel->password_hash = Yii::$app->security->generatePasswordHash($newPassword);
		$userModel->save();
	}
	
	public function changeEmail($password, $email) {
		$userModel = $this->getUserModel($password);
		$userModel->email = $email;
		$userModel->save();
	}
	
	private function getUserModel($password) {
		$userId = Yii::$app->user->identity->getId();
		$userModel = $this->model->find()->where(['id' => $userId])->one();
		if(empty($userModel) || empty($userModel->id)) {
			throw new UnauthorizedHttpException();
		}
		if(!Yii::$app->security->validatePassword($password, $userModel->password_hash)) {
			$error = new ErrorCollection();
			$error->add('password', 'account/auth', 'incorrect_password');
			throw new UnprocessableEntityHttpException($error);
		}
		return $userModel;
	}
}