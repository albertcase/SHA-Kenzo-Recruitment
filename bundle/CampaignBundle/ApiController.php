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
			$data = array('status' => 1);
			$this->dataPrint($data);
		} else {
			$this->statusPrint('0', 'failed');
		}
    }

}
