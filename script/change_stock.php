<?php
define('SITE_URL', dirname(dirname(__FILE__)));
require_once SITE_URL . '/Core/bootstrap.php';
include_once SITE_URL . '/config/config.php';
include_once SITE_URL . '/config/router.php';

$DatabaseAPI = new \Lib\DatabaseAPI();

$change = new ChangeStock($DatabaseAPI);
$change->setQuota('gift');
$change->setQuota('lottery');
echo 'update ok!';

class ChangeStock {

	private $db;

	public function __construct($db)
	{	
		$this->db = $db;
	}

	private function getLastQuota($type = 'gift')
	{
		$date = date('Y-m-d', time() - 24 * 3600);
		if($type == 'gift') {
			$sum = $this->db->checkGiftQuota($date, 1);
			$num = $this->db->loadGiftCount($date . '%');
			return $sum - $num;
		}

		if($type == 'lottery') {
			$sum = $this->db->checkGiftQuota($date, 2);
			$num = $this->db->loadLotteryCount($date . '%');
			return $sum - $num;
		}
	}

	public function setQuota($type = 'gift')
	{
		$date = date('Y-m-d');
		if($type == 'gift') {
			$last = $this->getLastQuota('gift');
			$this->db->setLastNum($date, 1, $last);
		}

		if($type == 'lottery') {
			$last = $this->getLastQuota('lottery');
			$this->db->setLastNum($date, 2, $last);
		}
	}

}

