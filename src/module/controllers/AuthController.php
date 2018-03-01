<?php
namespace yii2module\account\module\controllers;

use common\enums\rbac\PermissionEnum;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii2lab\helpers\Behavior;
use yii2module\account\domain\v1\forms\LoginForm;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\notify\domain\widgets\Alert;

/**
 * AuthController controller
 */
class AuthController extends Controller
{
	public $defaultAction = 'login';

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
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
					[
						'actions' => ['login'],
						'allow' => true,
						'roles' => ['?'],
					],
				],
			],
			'verb' => Behavior::verb([
				'logout' => ['post'],
			]),
		];
	}

	/**
	 * Logs in a user.
	 *
	 * @return mixed
	 */
	public function actionLogin()
	{
		$form = new LoginForm();
		$body = Yii::$app->request->post();
		$isValid = $form->load($body) && $form->validate();
		if ($isValid) {
			try {
				Yii::$app->account->auth->authenticationFromWeb($form->login, $form->password, $form->rememberMe);
				if(!$this->isBackendAccessAllowed()) {
					Yii::$app->account->auth->logout();
					Yii::$app->navigation->alert->create(['account/auth', 'login_access_error'], Alert::TYPE_DANGER);
					return $this->goHome();
				}
				Yii::$app->navigation->alert->create(['account/auth', 'login_success'], Alert::TYPE_SUCCESS);
				return $this->goBack();
			} catch(UnprocessableEntityHttpException $e) {
				$form->addErrorsFromException($e);
			}
		}
		
		return $this->render('login', [
			'model' => $form,
		]);
	}

	/**
	 * Logs out the current user.
	 *
	 * @return mixed
	 */
	public function actionLogout()
	{
		Yii::$app->account->auth->logout();
		Yii::$app->navigation->alert->create(['account/auth', 'logout_success'], Alert::TYPE_SUCCESS);
		return $this->goHome();
	}
	
	private function isBackendAccessAllowed()
	{
		if(APP != BACKEND) {
			return true;
		}
		if (Yii::$app->user->can(PermissionEnum::BACKEND_ALL)) {
			return true;
		}
		return false;
	}
	
}
