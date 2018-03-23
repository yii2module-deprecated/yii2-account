<?php

namespace yii2module\account\module\controllers;

use yii\web\Controller;
use yii2lab\helpers\Behavior;
use yii2module\account\domain\v2\entities\SecurityEntity;
use yii2module\account\module\forms\ChangePasswordForm;
use Yii;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\notify\domain\widgets\Alert;
use yii2module\account\domain\v2\forms\ChangeEmailForm;
use yii2module\account\module\helpers\SecurityMenu;

class SecurityController extends Controller {
	
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => Behavior::access('@'),
		];
	}
	
	public function actionIndex()
	{
		$menuInstance = new SecurityMenu();
		$menu = $menuInstance->toArray();
		$url = $menu[0]['url'];
		$this->redirect([SL . $url]);
	}
	
	public function actionEmail()
	{
		$model = new ChangeEmailForm();
		$body = Yii::$app->request->post('ChangeEmailForm');
		if (!empty($body)) {
			$model->setAttributes($body, false);
			if($model->validate()) {
				try {
					Yii::$app->account->security->changeEmail($model->getAttributes());
					Yii::$app->navigation->alert->create(['account/security', 'email_changed_success'], Alert::TYPE_SUCCESS);
				} catch (UnprocessableEntityHttpException $e) {
					$model->addErrorsFromException($e);
				}
			}
		} else {
			/** @var SecurityEntity $securityEntity */
			$securityEntity = Yii::$app->account->security->oneById(Yii::$app->user->id);
			$model->email = $securityEntity->email;
		}
		return $this->render('email', [
			'model' => $model,
		]);
	}
	
	public function actionPassword()
	{
		$model = new ChangePasswordForm();
		$body = Yii::$app->request->post('ChangePasswordForm');
		if(!empty($body)) {
			$model->setAttributes($body, false);
			if($model->validate()) {
				$bodyPassword = $model->getAttributes(['password', 'new_password']);
				try {
					Yii::$app->account->security->changePassword($bodyPassword);
					Yii::$app->navigation->alert->create(['account/security', 'password_changed_success'], Alert::TYPE_SUCCESS);
				} catch (UnprocessableEntityHttpException $e) {
					$model->addErrorsFromException($e);
				}
			}
		}
		return $this->render('password', [
			'model' => $model,
		]);
	}
	
}