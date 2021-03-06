<?php
namespace Lib;
/**
 * DatabaseAPI class
 */
class DatabaseAPI {

	private $db;

	private function connect() {
		$connect = new \mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
		$this->db = $connect;
		$this->db->query('SET NAMES UTF8');
		return $this->db;
	}
	/**
	 * Create user in database
	 */
	public function insertUser($userinfo){
		$nowtime = NOWTIME;
		$sql = "INSERT INTO `user` SET `openid` = ?, `created` = ?, `updated` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("sss", $userinfo->openid, $nowtime, $nowtime);
		if($res->execute()) 
			return $this->findUserByOpenid($userinfo->openid);
		else 
			return FALSE;
	}

	public function updateUser($data) {
		if ($this->findUserByOauth($data->openid)) {
			return TRUE;
		}
		$sql = "INSERT INTO `oauth` SET `openid` = ?, nickname = ?, headimgurl = ?";
		$res = $this->db->prepare($sql); 
		$res->bind_param("sss", $data->openid, $data->nickname, $data->headimgurl);
		if ($res->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function findUserByOauth($openid) {
		$sql = "SELECT id  FROM `oauth` WHERE `openid` = ?"; 
		$res = $this->db->prepare($sql);
		$res->bind_param("s", $openid);
		$res->execute();
		$res->bind_result($uid);
		if($res->fetch()) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Create user in database  
	 */
	public function findUserByOpenid($openid){
		$sql = "SELECT `uid`, `openid` FROM `user` WHERE `openid` = ?"; 
		$res = $this->connect()->prepare($sql);
		$res->bind_param("s", $openid);
		$res->execute();
		$res->bind_result($uid, $openid);
		if($res->fetch()) {
			$user = new \stdClass();
			$user->uid = $uid;
			$user->openid = $openid;
			return $user;
		}
		return NULL;
	}

	/**
	 * 
	 */
	public function saveInfo($data){
		if($this->findInfoByUid($data->uid)) {
			return $this->updateInfo($data);
		} else {
			return $this->insertInfo($data);
		}
	} 

	/**
	 * 
	 */
	public function insertInfo($data){
		$nowtime = NOWTIME;
		$sql = "INSERT INTO `info` SET `uid` = ?, `name` = ?, `mobile` = ?, `province` = ?, `city` = ?, `area` = ?, `address` = ?, `created` = ?, `updated` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("sssssssss", $data->uid, $data->name, $data->mobile, $data->province, $data->city, $data->area, $data->address, $nowtime, $nowtime);
		if($res->execute()) 
			return $res->insert_id;
		else 
			return FALSE;
	}

	/**
	 * 
	 */
	public function updateInfo($data){
		$nowtime = NOWTIME;
		$sql = "UPDATE `info` SET `name` = ?, `mobile` = ?, `province` = ?, `city` = ?, `area` = ?, `address` = ?, `updated` = ? WHERE `uid` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("ssssssss", $data->name, $data->mobile, $data->province, $data->city, $data->area, $data->address, $nowtime, $data->uid);
		if($res->execute()) 
			return $this->findInfoByUid($data->uid);
		else 
			return FALSE;
	}

	/**
	 * Create user in database
	 */
	public function findInfoByUid($uid){
		$sql = "SELECT `id`, `name`, `mobile`, `province`, `city`, `area`, `address` FROM `info` WHERE `uid` = ?"; 
		$res = $this->connect()->prepare($sql);
		$res->bind_param("s", $uid);
		$res->execute();
		$res->bind_result($id, $name, $mobile, $province, $city, $area, $address);
		if($res->fetch()) {
			$info = new \stdClass();
			$info->id = $id;
			$info->name = $name;
			$info->mobile = $mobile;
			$info->province = $province;
			$info->city = $city;
			$info->area = $area;
			$info->$address = $address;
			return $info;
		}
		return NULL;
	}


	public function checkOpenid($openid) {
		$sql = "SELECT `id` FROM `openid` WHERE `openid` = ?"; 
		$res = $this->connect()->prepare($sql);
		$res->bind_param("s", $openid);
		$res->execute();
		$res->bind_result($id);
		if($res->fetch()) {		
			return 1;
		}
		return 0;
	}

	public function checkGift($uid) {
        $sql = "SELECT `id` FROM `gift` WHERE `uid` = ?";
        $res = $this->connect()->prepare($sql);
        $res->bind_param("s", $uid);
        $res->execute();
        $res->bind_result($id);
        if($res->fetch()) {
            return 1;
        }
        return 0;
    }

    public function checkSubmit($uid) {
        $sql = "SELECT `id` FROM `info` WHERE `uid` = ?";
        $res = $this->connect()->prepare($sql);
        $res->bind_param("s", $uid);
        $res->execute();
        $res->bind_result($id);
        if($res->fetch()) {
            return 1;
        }
        return 0;
    }

	public function loadLotteryByUid($uid) {
		$sql = "SELECT `id` FROM `lottery` WHERE `uid` = ? and `status` = 1"; 
		$res = $this->connect()->prepare($sql);
		$res->bind_param("s", $uid);
		$res->execute();
		$res->bind_result($id);
		if($res->fetch()) {		
			return 1;
		}
		return 0;
	}

	public function loadLotteryCountByUid($uid) {
		$sql = "SELECT `id` FROM `lottery` WHERE `uid` = ?";
		$res = $this->connect()->prepare($sql);
		$res->bind_param("s", $uid);
		$res->execute();
		$res->bind_result($id);
		if($res->fetch()) {		
			return 1;
		}
		return 0;
	}

	public function loadLotteryCount($date) {
		$sql = "SELECT count(`id`) FROM `lottery` WHERE `status` = 1 AND `created` like ?";
		$res = $this->connect()->prepare($sql);
        $res->bind_param("s", $date);
		$res->execute();
		$res->bind_result($count);
		if($res->fetch()) {		
			return $count;
		}
		return 0;
	}
	

	public function setLottery($uid, $status) {
		$sql = "INSERT INTO `lottery` SET `uid` = ?, `status` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("ss", $uid, $status);
		if($res->execute()) 
			return $res->insert_id;
		else 
			return FALSE;
	}

	public function setGift($uid) {
		$sql = "INSERT INTO `gift` SET `uid` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("s", $uid);
		if($res->execute()) 
			return $res->insert_id;
		else 
			return FALSE;
	}

   // public function hasGift() {
   //     $sql = "SELECT count(`id`) AS count FROM `gift`";
   //     $res = $this->connect()->query($sql);
   //     $count = $res->fetch_all($resulttype = MYSQLI_ASSOC);
   //     if($count) {
   //         return $count[0]['count'];
   //     }
   //     return 0;
   // }

	public function setOpenid($openid) {
		$sql = "INSERT INTO `openid` SET `openid` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("s", $openid);
		if($res->execute()) 
			return TRUE;
		else 
			return FALSE;
	}

    public function checkGiftQuota($date, $type) {
        $sql = "SELECT `num`, `last_num` FROM `quota` WHERE `date` = ? AND `type` = ?";
        $res = $this->connect()->prepare($sql);
        $res->bind_param("ss", $date, $type);
        $res->execute();
        $res->bind_result($sum, $num);
        if($res->fetch()) {
            return $sum + $num;
        }
        return 0;
    }

    public function checkTdGiftQuota($date, $type) {
        $sql = "SELECT `num` FROM `quota` WHERE `date` = ? AND `type` = ?";
        $res = $this->connect()->prepare($sql);
        $res->bind_param("ss", $date, $type);
        $res->execute();
        $res->bind_result($sum);
        if($res->fetch()) {
            return $sum;
        }
        return 0;
    }

    public function getGift() {
    	$sql = "SELECT count(`id`) AS count FROM `gift`";
    	$res = $this->connect()->prepare($sql);
	    $res->execute();
        $res->bind_result($count);
        if($res->fetch()) {
            return $count;
        }
        return 0;
    }

    public function getLottery() {
    	$sql = "SELECT count(`id`) AS count FROM `lottery`";
    	$res = $this->connect()->prepare($sql);
	    $res->execute();
        $res->bind_result($count);
        if($res->fetch()) {
            return $count;
        }
        return 0;
    }

    public function getGiftQuota($date) {
    	$sumQuota = $this->checkGiftQuota($date, 1);
    	return $sumQuota;
    }

    public function getTdGiftQuota($date) {
    	$sumQuota = $this->checkTdGiftQuota($date, 1);
    	return $sumQuota;
    }

    public function loadGiftCount($date) {
		$sql = "SELECT count(`id`) FROM `gift` WHERE `createtime` like ?";
		$res = $this->connect()->prepare($sql);
        $res->bind_param("s", $date);
		$res->execute();
		$res->bind_result($count);
		if($res->fetch()) {		
			return $count;
		}
		return 0;
	}

	public function setLastNum($date, $type, $num) {
		$nowtime = NOWTIME;
		$sql = "UPDATE `quota` SET `last_num` = ?, `updated` = ? WHERE `date` = ? AND `type` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("ssss", $num, $nowtime, $date, $type);
		if($res->execute()) 
			return TRUE;
		else 
			return FALSE;
	}
}
