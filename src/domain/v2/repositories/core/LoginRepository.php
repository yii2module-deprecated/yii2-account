<?php

namespace yii2module\account\domain\v2\repositories\core;

use Yii;
use yii\web\NotFoundHttpException;
use yii2lab\core\domain\helpers\CoreHelper;
use yii2lab\core\domain\repositories\base\BaseActiveCoreRepository;
use yii2lab\domain\data\Query;
use yii2lab\domain\traits\CacheTrait;
use yii2lab\misc\enums\HttpHeaderEnum;
use yii2lab\misc\enums\TimeEnum;
use yii2lab\rest\domain\helpers\RestHelper;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\interfaces\repositories\LoginInterface;

class LoginRepository extends BaseActiveCoreRepository implements LoginInterface {
	
	use CacheTrait;
	
	public $point = 'user';
	
	public function isExistsByLogin($login) {
		try {
			$this->oneByLogin($login);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	public function oneById($id, Query $query = null) {
		$closure = function($data) {
			return $data instanceof LoginEntity && $data->id > 0;
		};
		$userEntity = $this->cacheMethod(__FUNCTION__, func_get_args(), TimeEnum::SECOND_PER_HOUR, $closure);
		return $userEntity;
	}
	
	public function oneByLogin($login) {
		return $this->oneById($login);
	}
	
	public function oneByToken($token, $type = null) {
		$url = CoreHelper::forgeUrl($this->version, 'auth');
		$headers = CoreHelper::getHeaders();
		$headers[HttpHeaderEnum::AUTHORIZATION] = $token;
		$response = RestHelper::get($url, [], $headers);
		
		$data = $response->data;
		
		if(empty($data['id'])) {
			if($response->status_code == 401) {
				throw new NotFoundHttpException();
			}
			/*if(empty($response->data['id'])) {
				throw new NotFoundHttpException();
			}*/
			// todo: crutch
			if($data['type'] == "yii2woop\\generated\\exception\\tps\\NotAuthenticatedException") {
				throw new NotFoundHttpException();
			}
			throw new NotFoundHttpException();
		}
		
		return $this->forgeEntity($response);
	}
	
	public function forgeEntity($data, $class = null) {
		/** @var LoginEntity $entity */
		$entity = parent::forgeEntity($data, $class);
		if(empty($entity->status)) {
			$entity->status = Yii::$domain->account->login->defaultStatus;
		}
		return $entity;
	}
	
}