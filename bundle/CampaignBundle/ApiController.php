<?php
namespace CampaignBundle;

use Core\Controller;


class ApiController extends Controller {

    public function __construct() {

    	global $user;

        parent::__construct();

        if(!$user->uid) {
	        $this->statusPrint('100', 'access deny!');
        }
    }

    /**
     * 发送短信验证码
     */
    public function phoneCodeAction()
    {
        $request = $this->request;
        $fields = array(
            'mobile' => array('cellphone', '121'),
        );
        $request->validation($fields);

        $ch = curl_init();
        $apikey = "b42c77ce5a2296dcc0199552012a4bd9";
        $mobile = $request->request->get('mobile');
        $code = rand(1000, 9999);
        $RedisAPI = new \Lib\RedisAPI();
        $RedisAPI->setPhoneCode($mobile, $code, '3600');
        $text = "【Kenzo凯卓】您的验证码是{$code}";
        $data = array('text'=>$text,'apikey'=>$apikey,'mobile'=>$mobile);
        curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        $json_data = curl_exec($ch);
        $array = json_decode($json_data,true);
//        echo '<pre>';print_r($array);exit;
        $data = array('status' => 1, 'msg' => 'send ok');
        $this->dataPrint($data);
    }

    /**
     * 验证短信验证码
     */
    public function checkPhoneCodeAction()
    {
        $request = $this->request;
        $fields = array(
            'phonecode' => array('notnull', '120'),
        );
        $request->validation($fields);
        $phoneCode = $request->request->get('phonecode');
        if(strtolower($phoneCode) == strtolower($_SESSION['phone-code'])) {
            $data = array('status' => 1, 'msg' => 'success');
        } else {
            $data = array('status' => 0, 'msg' => 'phone code is failed');
        }
        $this->dataPrint($data);
    }

    private function checkMsgCode($mobile, $msgCode) {
        $RedisAPI = new \Lib\RedisAPI();
        $code = $RedisAPI->get($mobile);
        if($code == $msgCode) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证验证码是否正确
     */
    public function checkPictureAction()
    {
    	$request = $this->request;
    	$fields = array(
			'picture' => array('notnull', '120'),
			);
		$request->validation($fields);
		$picture = $request->request->get('picture');
		if(strtolower($picture) == strtolower($_SESSION['captcha-protection'])) {
            $data = array('status' => 1, 'msg' => 'success');
		} else {
		    unset($_SESSION['captcha-protection']);
            $data = array('status' => 0, 'msg' => 'picture code is failed');
		}
		$this->dataPrint($data);
    }

    /**
     * 获取图片验证码
     */
    public function pictureCodeAction()
    {
		$captcha = new \Lib\Captcher(150, 65);
		$captchaImage = $captcha->generate();
		$captchaText = $captcha->getCaptchaText();
		$_SESSION['captcha-protection'] = $captchaText;
		$picture = base64_encode($captchaImage);
		$data = array('status' => 1, 'picture' => $picture);
		$this->dataPrint($data);
		// return base64_encode($captchaImage);
		// echo "<img src='data:image/jpeg;base64," . base64_encode($captchaImage) . "'>";
    }

    public function isloginAction() {

    	global $user;

    	$DatabaseAPI = new \Lib\DatabaseAPI();
    	$info = $DatabaseAPI->findInfoByUid($user->uid);
    	if ($info) {
    		$data = array('status' => 1, 'msg' => $info);
			$this->dataPrint($data);
    	} else {
    		$data = array('status' => 0, 'msg' => '未提交');
			$this->dataPrint($data);
    	}
    }


    public function submitAction() {

    	global $user;
    	if(!$user->uid) {
	        $this->statusPrint('100', 'access deny!');
        }
    	$request = $this->request;
    	$fields = array(
			'name' => array('notnull', '120'),
			'mobile' => array('cellphone', '121'),
			'msgCode' => array('notnull', '120'),
			'province' => array('notnull', '120'),
			'city' => array('notnull', '120'),
			'area' => array('notnull', '120'),
			'address' => array('notnull', '120'),
		);
		$request->validation($fields);
		$DatabaseAPI = new \Lib\DatabaseAPI();
		$data = new \stdClass();
		$data->uid = $user->uid;
		$data->name = $request->request->get('name');
		$data->mobile = $request->request->get('mobile');
        $msgCode = $request->request->get('msgCode');
        if(!$this->checkMsgCode($data->mobile, $msgCode)) {
            $data = array('status' => 2, 'msg'=> '手机验证码错误', 'userStatus' => $user->status);
            $this->dataPrint($data);
        }
		$data->province = $request->request->get('province');
		$data->city = $request->request->get('city');
		$data->area = $request->request->get('area');
		$data->address = $request->request->get('address');
		if($DatabaseAPI->saveInfo($data)) {
            $user->status['issubmit'] = 1;
 			$data = array('status' => 1, 'msg'=> '信息提交成功', 'userStatus' => $user->status);
			$this->dataPrint($data);
		} else {
            $user->status['issubmit'] = 0;
            $data = array('status' => 0, 'msg'=> '信息提交失败', 'userStatus' => $user->status);
            $this->dataPrint($data);
		}
    }

    public function giftAction() {
        global $user;
        $DatabaseAPI = new \Lib\DatabaseAPI();
        if(!$this->checkQuotaTime()) {
            $data = array('status' => 5, 'msg'=> '今日活动将于上午10点开启，请稍后再来哦！', 'userStatus' => $user->status);
            $this->dataPrint($data);
        }
        $checknew = $DatabaseAPI->checkOpenid($user->openid);
        if (!$checknew) {
            $date = date('Y-m-d');
            //新用户申领
            //今天的小样领取完毕
            //已经领取过小样
            if($DatabaseAPI->checkGift($user->uid)) {
                $data = array('status' => 4, 'msg'=> '对不起，您已经领取过小样！', 'userStatus' => $user->status);
                $this->dataPrint($data);
            }
            $sum = $DatabaseAPI->checkGiftQuota($date, 1);
            $count = $DatabaseAPI->loadGiftCount($date . '%');
            if($count>=$sum) {
                //小样全部领取完毕
                if($this->checkLastQuota($date)) {
                    $data = array('status' => 3, 'msg'=> '小样已经全部领空。', 'userStatus' => $user->status);
                    $this->dataPrint($data);
                } else {
                    $data = array('status' => 2, 'msg'=> '今天小样已经领取完毕，请明天再来。', 'userStatus' => $user->status);
                    $this->dataPrint($data);
                }
            }
            //领取小样
            $DatabaseAPI->setGift($user->uid);
            $user->status['isgift'] = 1;
            $data = array('status' => 1, 'msg'=> '小样领取成功', 'userStatus' => $user->status);
            $this->dataPrint($data);
        } else {
            $data = array('status' => 0, 'msg'=> '非新关注用户没有领取资格', 'userStatus' => $user->status);
            $this->dataPrint($data);
        }
    }

    public function lotteryAction() {
    	global $user;
        // if(!$this->checkQuotaTime()) {
        //     $data = array('status' => 5, 'msg'=> '活动未开始，！', 'userStatus' => $user->status);
        //     $this->dataPrint($data);
        // }
    	$databaseAPI = new \Lib\DatabaseAPI();
    	$date = date('Y-m-d');
    	//已中奖
    	$lottery = $databaseAPI->loadLotteryByUid($user->uid);
    	if ($lottery) {
			$databaseAPI->setLottery($user->uid, 2);
            $data = array('status' => 3, 'msg'=> '您已获奖', 'userStatus' => $user->status);
			$this->dataPrint($data);
		}
		//奖发完
        $sum = $databaseAPI->checkGiftQuota($date, 2);
		$count = $databaseAPI->loadLotteryCount($date . '%');
		if ($count>=$sum) {
//			$databaseAPI->setLottery($user->uid, 2);
            $data = array('status' => 2, 'msg'=> '今天的奖品已经发没，请明天再来！', 'userStatus' => $user->status);
			$this->dataPrint($data);
		}
		//中奖率
		$rand = mt_rand(1,10000);
		if ($rand<=4) {
			$databaseAPI->setLottery($user->uid, 1);
            $user->status['isluckydraw'] = 1;
            $data = array('status' => 1, 'msg'=> '恭喜中奖', 'userStatus' => $user->status);
			$this->dataPrint($data);
		}
		$databaseAPI->setLottery($user->uid, 2);
        $data = array('status' => 0, 'msg'=> '遗憾未中奖', 'userStatus' => $user->status);
		$this->dataPrint($data);
		
    }

   //判断小样是否已经领取没了
   private function checkGiftNum($db, $quota) {
       $count = $db->hasGift();
       if($count == $quota) {
           return FALSE;
       } else {
           return TRUE;
       }
   }

   //查小样的数量
   private function getGiftQuota($db, $date) {
        $quota = $db->checkGiftQuota($date, 1);
        $num = $db->getGift();
        if($quota >= 0) {
            return true;
        } else {
            return false;
        }
   }

   //查奖品的数量
   private function getLotteryQuota($db, $date) {
       $quota = $db->checkGiftQuota($date, 2);
       if($quota > 0) {
           return true;
       } else {
           return false;
       }
   }

   //判断小样是否是都没了
   private function checkLastQuota($date) {
       $lastDate = '2017-08-19';
       if($lastDate == $date) {
           return true;
       } else {
           return false;
       }
   }

   // 每天十点放库存
   private function checkQuotaTime() {
        $time = date("H");
        if($time >= 10) {
            return true;
        } else {
            return false;
        }
    }

}
