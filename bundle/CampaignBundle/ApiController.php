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
		$data->province = $request->request->get('province');
		$data->city = $request->request->get('city');
		$data->area = $request->request->get('area');
		$data->address = $request->request->get('address');
		if($DatabaseAPI->saveInfo($data)) {
			$checknew = $DatabaseAPI->checkOpenid($user->openid);
			if (!$checknew) {
				//新用户申领
                //判断是否已经领取完$this->statusPrint('2', '礼品已经领取完！');
                if(!$this->checkGiftNum($DatabaseAPI, GIFT_QUOTA)) {
                    $this->statusPrint('2', '礼品已经领取完！');
                }
                $DatabaseAPI->setGift($user->uid);
			}
			$data = array('status' => 1);
			$this->dataPrint($data);
		} else {
			$this->statusPrint('0', 'failed');
		}
    }

    public function lotteryAction() {

    	global $user;

    	$databaseAPI = new \Lib\DatabaseAPI();
    	//已中奖
    	$lottery = $databaseAPI->loadLotteryByUid($user->uid);
    	if ($lottery) {
			$databaseAPI->setLottery($user->uid, 2);
			$data = array('status' => 2, 'msg'=>'谢谢参与');
			$this->dataPrint($data);
		}
		//奖发完
		$count = $databaseAPI->loadLotteryCount();
		if ($count>=7) {
			$databaseAPI->setLottery($user->uid, 2);
			$data = array('status' => 2, 'msg'=>'谢谢参与');
			$this->dataPrint($data);
		}
		//中奖率
		$rand = mt_rand(1,10000);
		if ($rand<=4) {
			$databaseAPI->setLottery($user->uid, 1);
			$data = array('status' => 1, 'msg'=>'恭喜中奖');
			$this->dataPrint($data);
		}
		$databaseAPI->setLottery($user->uid, 2);
		$data = array('status' => 2, 'msg'=>'谢谢参与');
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

   //查库存
   private function getGiftQuota($db, $date)
   {

   }

}
