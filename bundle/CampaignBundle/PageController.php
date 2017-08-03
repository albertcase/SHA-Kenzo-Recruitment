<?php
namespace CampaignBundle;

use Core\Controller;

class PageController extends Controller {

//	public function indexAction() {
//		// ini_set("display_errors", 1);
//		global $user;
//		if (!$user->uid) {
//			$this->redirect('/wechat/curio/callback');
//		}
//		//var_dump($user);exit;
//		$subscribed = $this->subscribed($user->openid);
//		if (!$subscribed) {
//			//未关注
//			$this->render('unfollow');
//		}
//		//关注
//		$databaseAPI = new \Lib\DatabaseAPI();
//        $isOld = $databaseAPI->checkOpenid($user->openid);
//        if (!$isOld) {
//            $this->render('newfollow');
//        }
//        $this->render('followed');
//		$checknew = $databaseAPI->checkOpenid($user->openid);
//		if ($checknew) {
//			$this->render('followed');
//		}
//		$info = $databaseAPI->findInfoByUid($user->uid);
//		if (!$info) {
//			$this->render('newfollow');
//		}
//		$this->render('followed');
//	}

    /**
     * 入口
     */
    public function indexAction()
    {
        global $user;
        $subscribed = $this->subscribed($user->openid);
		if (!$subscribed) {
            $this->render('unfollow');
        }
        $this->render('followed',  array('userStatus' => $user->status, 'quota' => $user->quota));
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

	public function runopenidAction() {
		ini_set("display_errors", 1);
		set_time_limit(0);
		$count = 0;
		$databaseAPI = new \Lib\DatabaseAPI();
	  	$next_openid = '';
	  	while (true) {
	  		$openidlist = $this->getOpenidList($next_openid);
	  		if ($openidlist['count']==0) {
	  			break;
	  		}
	  		$next_openid = $openidlist['next_openid'];
	  		$list = $openidlist['data']['openid'];
	  		for($i=0;$i<count($list);$i++) {
	  			if ($databaseAPI->setOpenid($list[$i])) {
	  				$count++;
	  			}
	  		}
	  	}
	  	echo $count;
	  	exit;
	}

	public function getOpenidList($next_openid = '') {
		$access_token = file_get_contents("http://kenzowechat.samesamechina.com/weixin/getaccesstoken");
		$data = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token."&next_openid=".$next_openid);
		return json_decode($data,true);
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
			'openid' => array('notnull', '120'),
		);
		$request->validation($fields);
		$userAPI = new \Lib\UserAPI();
		$user = $userAPI->userLogin($request->query->get('openid'));
		if(!$user) {
			$userAPI->userRegister($request->query->get('openid'));
		}
		echo 'user:login:'.$request->query->get('openid');
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
		setcookie('_user', '', time(), '/');
		$this->statusPrint('success');
	}

}