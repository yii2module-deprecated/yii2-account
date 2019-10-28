<?php

namespace yii2module\account\domain\v2\entities;

use App;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

use yii\web\NotFoundHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\values\TimeValue;
use yii2module\account\domain\v2\helpers\LoginHelper;
use yii2woop\operation\domain\v2\helpers\ExceptionHelper;
use yii2woop\operation\domain\v2\helpers\TpsHelper;

/**
 * Class ActivityEntity
 * 
 * @package yii2module\account\domain\v2\entities
 * 
 * @property $login
 * @property $sms_code
 * @property $key
 * @property $sms_date_expiration
 * @property $key_date_create

 */
class AppIdentityEntity extends BaseEntity {

	public $login;
	public $sms_code;
	public $sms_date_expiration;
	public $key;
	public $key_date_create;


	const AVAILABLE_OPERATORS = ['activ', 'kcell'];

	public function rules()
	{
		return [
			[['login', 'sms_code'], 'required'],
			[['login'], 'operatorValidate'],
			[['login', 'sms_code', 'key'], 'string'],
		];
	}

	/**
	 * @param string $login
	 */
	public function setLogin($login): void
	{
		$this->login = LoginHelper::pregMatchLogin($login);
	}

	public static function tableName()
	{
		return '{{%app_identity}}';
	}
	/**
	 * @return mixed
	 */
	public function getSmsCode()
	{
		return $this->sms_code;
	}

	/**
	 * @return mixed
	 */
	public function getSmsDateExpiration()
	{
		return $this->sms_date_expiration;
	}

	/**
	 * @return mixed
	 */
	public function getLogin()
	{
		return $this->login;
	}

	/**
	 * @return mixed
	 */
	public function getKeyDateCreate()
	{
		return $this->key_date_create;
	}

	/**
	 * @return mixed
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * check operator; Only activ and kcell can use app registration
	 *
	 * @param string $attribute_name
	 *
	 * @return bool
	 * @throws UnprocessableEntityHttpException
	 */
	public function operatorValidate($attribute_name): bool
	{
		try{
			$operator = TpsHelper::getMobileOperator($this->$attribute_name);
		} catch (\Exception $e){
			ExceptionHelper::tryPayment($e);
		}

		if (!in_array($operator, self::AVAILABLE_OPERATORS)) {
			$errors = new ErrorCollection();
			$errors->add('login', Yii::t('operation/errors', 'unavailable_mobile_operator'));
			throw new UnprocessableEntityHttpException($errors);
			return false;
		}
		return true;
	}

	/**
	 * @param mixed $sms_code
	 */
	public function setSmsCode($sms_code): void
	{
		$this->sms_code =  $sms_code;
	}


}
