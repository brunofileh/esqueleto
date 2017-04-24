<?php

namespace projeto\base;

use Yii;

use app\modules\admin\models\TabMenus;
use app\modules\admin\models\TabModulosSearch;
use app\modules\admin\models\VisUsuariosPerfisSearch;
use app\modules\admin\models\VisPerfisFuncionalidadesAcoesModulosSearch;
use app\modules\admin\models\RlcUsuariosPerfisSearch;
use app\models\TabParametrosSearch;
use \app\modules\admin\models\TabModulos;

class Module extends \yii\base\Module
{
	use \projeto\Atalhos;
	// rota padrão do módulo ou a sua página inicial
	public $defaultRoute = 'inicio';
	// informações sobre o módulo, menu e perfil
	public $_info = null;
	
	public function init()
	{
		parent::init();
		
		// verifica bloqueio dos módulos
		$identity = Yii::$app->user->identity;
		if ($identity !== null && static::isModuloBloqueadoAcessoInterno($this->id, $identity->getId())) {
			Yii::$app->session->setFlash('danger', 'Módulo bloqueado para acesso de usuários internos.');
			return Yii::$app->response->redirect(['/']);
		}
		
		if ($identity !== null && static::isModuloBloqueadoAcessoExterno($this->id, $identity->getId())) {
			Yii::$app->session->setFlash('danger', 'Módulo bloqueado para acesso de usuários externos.');
			return Yii::$app->response->redirect(['/']);
		}
		
		// configuração dos atalhos
		$this->configAtalhos();
	}

	public static function isModuloBloqueadoAcessoInterno($moduloID, $userID)
	{
		$user = \app\modules\admin\models\TabUsuarios::findOne($userID);
		if ($user->cod_prestador_fk === null) {
			$cod_perfil_fk = null;
			$modulo = TabModulos::findOne(['id' => $moduloID]);
			$modulos = VisUsuariosPerfisSearch::getModulosPerfisUsuario($userID);
			
			foreach ($modulos as $m) {
				if ($m['cod_modulo_fk'] == $modulo->cod_modulo) {
					$cod_perfil_fk = $m['cod_perfil_fk'];
					break;
				}
			}
			
			$interno = TabParametrosSearch::findOne([
				'num_ano_ref' => Yii::$app->params['ano-ref'], 
				'sgl_parametro' => 'sistema-bloqueio-usr-interno', 
				'modulo_fk' => $modulo->cod_modulo
			]);
			
			return (isset($interno) && $interno->vlr_parametro == 1) && $cod_perfil_fk != 10;
		}
		else {
			return false;
		}
	}
	
	public static function isModuloBloqueadoAcessoExterno($moduloID, $userID)
	{
		$user = \app\modules\admin\models\TabUsuarios::findOne($userID);
		if ($user->cod_prestador_fk !== null) {
			$cod_perfil_fk = null;
			$modulo = TabModulos::findOne(['id' => $moduloID]);
			$modulos = VisUsuariosPerfisSearch::getModulosPerfisUsuario($userID);
			
			foreach ($modulos as $m) {
				if ($m['cod_modulo_fk'] == $modulo->cod_modulo) {
					$cod_perfil_fk = $m['cod_perfil_fk'];
					break;
				}
			}
			
			$externo = TabParametrosSearch::findOne([
				'num_ano_ref' => \Yii::$app->params['ano-ref'], 
				'sgl_parametro' => 'sistema-bloqueio-usr-externo', 
				'modulo_fk' => $modulo->cod_modulo
			]);
			
			if (isset($externo) && $externo->vlr_parametro == 1) {
			
				$externoSit = TabParametrosSearch::findOne([
					'num_ano_ref' => \Yii::$app->params['ano-ref'], 
					'sgl_parametro' => 'sistema-bloqueio-usr-externo-sit', 
					'modulo_fk' => $modulo->cod_modulo
				]);
				
				$arrSit = array_map(function ($item) {
					return \projeto\Util::attrId($item);
				}, \yii\helpers\Json::decode($externoSit->vlr_parametro));
				
				$rlcModPsv = \app\models\RlcModulosPrestadores::find()->where([
					'cod_prestador_fk' => $user->cod_prestador_fk,
					'cod_modulo_fk' => $modulo->cod_modulo,
				])->one();

				$part = \app\modules\drenagem\models\TabParticipacoes::find()->where([
					'cod_modulo_prestador_fk' => $rlcModPsv->cod_modulo_prestador,
					'ano_ref' => \Yii::$app->params['ano-ref'],
				])->one();
				
				$externoMun = TabParametrosSearch::findOne([
					'num_ano_ref' => Yii::$app->params['ano-ref'], 
					'sgl_parametro' => 'sistema-bloqueio-usr-externo-mun', 
					'modulo_fk' => $modulo->cod_modulo
				]);
				
				$munLiberado =  false;
				if ($externoMun->vlr_parametro) {
					$arrMun = \yii\helpers\Json::decode($externoMun->vlr_parametro);
					$munLiberado = in_array($rlcModPsv->cod_municipio_fk, $arrMun);
				}
				
				$bloqueado = (in_array($part->cod_situacao_preenchimento_fk, $arrSit) && in_array($cod_perfil_fk, [26, 27]));
				
				if (!$bloqueado || ($externoMun->vlr_parametro && $munLiberado)) {
					return false;
				}
				else {
					return true;
				}
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}
	
	public function setInfo(array $info)
	{
		$this->_info = $info;
	}

	public function getInfo()
	{
		$moduleID = $this->id;
		// informações sobre o menu em questão

		$info = TabModulosSearch::getInfo($moduleID);
		if ($info) {
			$strRequestedRoute = ltrim(rtrim($this->module->requestedRoute, '/'), '/');
			$arrRequestedRoute = explode('/', $strRequestedRoute);
			$route = '';
			$info['menu-item'] = [];
			# /modulo/controller/action | /modulo/controller
			if (count($arrRequestedRoute) > 1) {
				$ctrlBaseUrl = "/{$arrRequestedRoute[0]}/{$arrRequestedRoute[1]}";
			}
			# /modulo
			else {
				$ctrlBaseUrl = "/{$arrRequestedRoute[0]}/{$this->defaultRoute}";
			}
			
			$getData = function() use($ctrlBaseUrl) {
				return TabMenus::findOneAsArray(['txt_url' => $ctrlBaseUrl]);
			};
			
			
			if (Yii::$app->params['habilitar-cache-global']) {
				$menuInfoCacheKey = [Yii::$app->session->id, 'modulo', $moduleID, 'menu-url', $ctrlBaseUrl];
				if (($data = Yii::$app->cache->get($menuInfoCacheKey)) === false) {
					$data = $getData();
					Yii::$app->cache->set($menuInfoCacheKey, $data);
				}
			}
			else {
				$data = $getData();
			}
			$info['menu-item'] = $data;
		}
		$user = Yii::$app->user;
		// informações do usuário em questão
		if (!$user->isGuest) {
			$userID = $user->identity->getId();
			$getData = function () use ($moduleID, $userID) {
				return VisUsuariosPerfisSearch::getUsuarioPerfilModulos($moduleID, $userID);
			};
			// usuario perfil
			if (Yii::$app->params['habilitar-cache-global']) {
				$usrPerfilCacheKey = [Yii::$app->session->id, 'modulo', $moduleID, 'usuario-perfil', $userID];
				if (($data = Yii::$app->cache->get($usrPerfilCacheKey)) === false) {
					$data = $getData();
					Yii::$app->cache->set($usrPerfilCacheKey, $data);
				}
			}
			else {
				$data = $getData();
			}
			$info['usuario-perfil'] = $data;
			
	
			//qntidade acesso por modulo do usuario		
			$fnModuloCacheKey = [
				Yii::$app->session->id, 
				'modulo', $moduleID, 
				'usuario-modulo', 
				$userID,
			];
			
			$getData = function () use ($info, $fnModuloCacheKey) {
				return RlcUsuariosPerfisSearch::atualizarQtdAcesso(
					$info['usuario-perfil'], $fnModuloCacheKey
				);
			};


			if (Yii::$app->params['habilitar-cache-global']) {				
	
				if (($data = Yii::$app->cache->get($fnModuloCacheKey)) === false) {
					$data = $getData();
					Yii::$app->cache->set($fnModuloCacheKey, $data);
				}
			}
			else {

				$data = $getData();
			}
			
			$info['usuario-modulo'] = $data;
			
			
			// funcionalidade ação
			$getData = function () use ($info, $userID) {
				return VisPerfisFuncionalidadesAcoesModulosSearch::searchPermissoes(
					$info['usuario-perfil']['cod_perfil_fk'], $userID
				);
			};
			if (Yii::$app->params['habilitar-cache-global']) {
				$fnAcaoCacheKey = [
					Yii::$app->session->id, 
					'modulo', $moduleID, 
					'usuario-perfil', $info['usuario-perfil']['cod_perfil_fk'],
					'usuario', $userID
				];
				if (($data = Yii::$app->cache->get($fnAcaoCacheKey)) === false) {
					$data = $getData();
					Yii::$app->cache->set($fnAcaoCacheKey, $data);
				}
			}
			else {
				$data = $getData();
			}
			$info['funcionalidade-acao'] = $data;	
		}
			
		return $info;
	}
}
