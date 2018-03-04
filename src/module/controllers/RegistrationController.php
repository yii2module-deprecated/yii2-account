<?php
namespace yii2module\account\module\controllers;

use yii2woop\profile\module\forms\ProfileForm;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v1\forms\RegistrationForm;
use yii2module\account\module\forms\SetSecurityForm;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii2lab\notify\domain\widgets\Alert;

class RegistrationController extends Controller
{
	
	public $defaultAction = 'create';

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'actions' => ['create', 'set-password'],
						'allow' => true,
						'roles' => ['?'],
					],
					[
						'actions' => ['set-name', 'set-address'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

	/**
	 * Signs user up.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new RegistrationForm();
		$model->scenario = RegistrationForm::SCENARIO_CHECK;
		$callback = function($model) {
			Yii::$app->account->registration->activateAccount($model->login, $model->activation_code);
			$session['login'] = $model->login;
			$session['activation_code'] = $model->activation_code;
			Yii::$app->session->set('registration', $session);
			return $this->redirect(['/user/registration/set-password']);
		};
		$this->validateForm($model,$callback);
		return $this->render('create', [
			'model' => $model,
		]);
	}
	
	public function actionSetPassword()
	{
		$session = Yii::$app->session->get('registration');
		if(empty($session['login']) || empty($session['activation_code'])) {
			return $this->redirect(['/user/registration']);
		}
		$isExists = Yii::$app->account->repositories->temp->isExists($session['login']);
		if(!$isExists) {
			Yii::$app->navigation->alert->create(['account/registration', 'temp_user_not_found'], Alert::TYPE_DANGER);
			return $this->redirect(['/user/registration']);
		}
		$model = new SetSecurityForm();
		$callback = function($model) use ($session) {
			Yii::$app->account->registration->createTpsAccount($session['login'], $session['activation_code'], $model->password, $model->email);
			Yii::$app->account->auth->authenticationFromWeb($session['login'], $model->password, true);
			return $this->redirect(['/user/registration/set-name']);
		};
		$this->validateForm($model,$callback);
		return $this->render('set_password', [
			'model' => $model,
			'login' => $session['login'],
		]);
	}
	
	public function actionSetName()
	{
		$model = new ProfileForm();
		$callback = function($model) {
			Yii::$app->profile->person->updateSelf($model->toArray());
			return $this->redirect(['/user/registration/set-address']);
		};
		$this->validateForm($model,$callback);
		return $this->render('set_name', [
			'model' => $model,
		]);
	}
	
	public function actionSetAddress()
	{
		$model = new ProfileForm();
		$callback = function($model) {
			Yii::$app->profile->address->updateSelf($model->toArray());
			return $this->goHome();
		};
		$this->validateForm($model,$callback);
		return $this->render('set_address', [
			'model' => $model,
		]);
	}
	
	private function validateForm($form, $callback) {
		$body = Yii::$app->request->post();
		$isValid = $form->load($body) && $form->validate();
		if ($isValid) {
			try {
				return call_user_func_array($callback, [$form]);
			} catch (UnprocessableEntityHttpException $e) {
				$form->addErrorsFromException($e);
			}
		}
	}
}
