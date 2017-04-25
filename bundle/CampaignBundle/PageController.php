<?php
namespace CampaignBundle;

use Core\Controller;

class PageController extends Controller {

	public function indexAction() {	
		ini_set("display_errors", 1);
		global $user;
		if (!$user) {
			$this->redirect('/wechat/curio/callback');
		}
		$subscribed = $this->subscribed($user->openid);
		if (!$subscribed) {
			//未关注
			$this->render('unfollow');
		}
		//关注
		$databaseAPI = new \Lib\DatabaseAPI();
		$checknew = $databaseAPI->checkOpenid($user->openid);
		if (!$checknew) {
			$this->render('followed');
		}
		$lottery = $databaseAPI->loadLotteryByUid($user->uid);
		if (!$lottery) {
			$this->render('newfollow');
		}
		$this->render('followed');
	}

	public function jssdkConfigJsAction() {
		$request = $this->Request();
		$fields = array(
		    'url' => array('notnull', '120'),
	    );
		$request->validation($fields);
		$url = urldecode($request->query->get('url'));
	  	print $config = file_get_contents("http://kenzowechat.samesamechina.com/weixin/jssdk?url=".$url);
	  	exit;
	}

	public function subscribed($openid) {
	  	return $subscribed = file_get_contents("http://kenzowechat.samesamechina.com/weixin/subscribed?openid=".$openid);
	}

	public function jssdkConfig($url = '') {
		$RedisAPI = new \Lib\RedisAPI();
		$jsapi_ticket = $RedisAPI->getJSApiTicket();
		$wechatJSSDKAPI = new \Lib\JSSDKAPI();
		return $wechatJSSDKAPI->getJSSDKConfig(APPID, $jsapi_ticket, $url);
	}

	public function resultAction() {	
		global $user;

		$request = $this->request;
		$fields = array(
			'id' => array('notnull', '120'),
		);
		$request->validation($fields);
		$id = $request->query->get('id');
		$databaseAPI = new \Lib\DatabaseAPI();
		$product = $databaseAPI->loadMakeById($id);
		$ismy = 1;
		//绑定
		if ($user->uid != $product->uid) {
			$ismy = 0;
			$databaseAPI->bandShare($user->uid, $product->uid);
			$databaseAPI->bandShare($product->uid, $user->uid);
		}
		
		$this->render('match', array('ismy' => $ismy));
	}

	public function loginAction() {
		$request = $this->request;
		$fields = array(
			'id' => array('notnull', '120'),
		);
		$request->validation($fields);
		$id = $request->query->get('id');
		$user = new \stdClass();
		$user->uid = $id;
		$user->openid = 'openid_'.$id;
		$user->nickname = 'user_'.$id;
		$user->headimgurl = '111';
		setcookie('_user0206', json_encode($user), time()+3600*24*30, '/');
		echo 'user:login:'.$id;
		exit;

	}

	public function clearDataAction() {
		exit;
		$databaseAPI = new \Lib\DatabaseAPI();
		$databaseAPI->clearMake();
		$data = array('status' => 1, 'msg' => 'clear');
			$this->dataPrint($data);
		exit;

	}

	public function clearCookieAction() {
		setcookie('_user', json_encode($user), time(), '/');
		$this->statusPrint('success');
	}

}