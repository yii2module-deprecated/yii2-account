<?php

namespace yii2module\account\domain\v1\repositories\tps;

use Yii;
use yii2module\account\domain\v1\interfaces\repositories\RestorePasswordInterface;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\repositories\TpsRepository;
use yii2woop\generated\exception\tps\TooOftenPasswordResetException;
use yii2woop\generated\exception\tps\InsufficientPrivilegesException;
use yii2woop\generated\transport\TpsCommands;

class RestorePasswordRepository extends TpsRepository implements RestorePasswordInterface {

	public function requestNewPassword($login, $mail = null) {
	
		if($mail){
			$body = Yii::t("account/password", 'email_template_body');
			$subject = Yii::t("account/password", 'email_template_subject');
			try {
				TpsCommands::sendConfirmationEmail($login,$body,$subject,strval($mail))->send();
			} catch(InsufficientPrivilegesException $e) {
				$error = new ErrorCollection();
				$error->add('email', 'tps', 'InsufficientPrivilegesException');
				throw new UnprocessableEntityHttpException($error);
			}
		}else{
			$request = TpsCommands::checkUserOperationAndSendSMS($login);
			try {
				$result = $request->send();
				
			} catch(TooOftenPasswordResetException $e) {
				$error = new ErrorCollection();
				$error->add('login', 'tps', 'TooOftenPasswordResetException');
				throw new UnprocessableEntityHttpException($error);
			}
		}
	
	}
	
	public function checkActivationCode($login, $code) {
		$request = TpsCommands::passwordChangeByAuthKey($login, '', $code);
		$result = $request->send();
	}
	
	public function setNewPassword($login, $code, $password) {
		$request = TpsCommands::passwordChangeByAuthKey($login, $password, $code);
		$result = $request->send();
	}
	
	public function isExists($login) {
		return $this->domain->repositories->login->isExistsByLogin($login);
	}
}