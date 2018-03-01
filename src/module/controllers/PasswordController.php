<?php
namespace yii2module\account\module\controllers;

use yii2lab\helpers\Behavior;
use yii2module\account\module\forms\RestorePasswordForm;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii2lab\notify\domain\widgets\Alert;

/**
 * PasswordController controller
 */
class PasswordController extends Controller
{
	public $defaultAction = 'request';
	
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => Behavior::access('?'),
		];
	}
	
	public function actionRequest() {
		$model = new RestorePasswordForm();
		$model->setScenario(RestorePasswordForm::SCENARIO_REQUEST);
		if(Yii::$app->request->isPost) {
			$body = Yii::$app->request->post('RestorePasswordForm');
			$model->setAttributes($body, false);
			try {
				Yii::$app->account->restorePassword->request($model->login);
				$session['login'] = $model->login;
				Yii::$app->session->set('restore-password', $session);
				return $this->redirect(['/user/password/check']);
			} catch (UnprocessableEntityHttpException $e){
				$model->addErrorsFromException($e);
			}
		}
		return $this->render('request', ['model' => $model]);
	}
	
	public function actionCheck() {
		$model = new RestorePasswordForm();
		$model->setScenario(RestorePasswordForm::SCENARIO_CHECK);
		$session = Yii::$app->session->get('restore-password');
		$model->login = $session['login'];
		if(Yii::$app->request->isPost) {
			$body = Yii::$app->request->post('RestorePasswordForm');
			$model->setAttributes($body, false);
			try {
				
				Yii::$app->account->restorePassword->checkActivationCode($model->login, $model->activation_code);
				$session['activation_code'] = $model->activation_code;
				Yii::$app->session->set('restore-password', $session);
				return $this->redirect(['/user/password/confirm']);
			} catch (UnprocessableEntityHttpException $e){
				$model->addErrorsFromException($e);
			}
		}
		return $this->render('check', ['model' => $model]);
	}
	
	public function actionConfirm()
	{
		$model = new RestorePasswordForm();
		$model->setScenario(RestorePasswordForm::SCENARIO_CONFIRM);
		$session = Yii::$app->session->get('restore-password');
		$model->login = $session['login'];
		$model->activation_code = $session['activation_code'];
		if(Yii::$app->request->isPost) {
			$body = Yii::$app->request->post('RestorePasswordForm');
			$model->setAttributes($body, false);
			try {
				Yii::$app->account->restorePassword->confirm($model->login, $model->activation_code, $model->password);
				Yii::$app->navigation->alert->create(['account/password', 'new_password_saved_success'], Alert::TYPE_SUCCESS);
				return $this->redirect('/' . Yii::$app->user->loginUrl[0]);
			} catch (UnprocessableEntityHttpException $e){
				$model->addErrorsFromException($e);
			}
		}
		return $this->render('reset', [
			'model' => $model,
		]);
	}
	
}
