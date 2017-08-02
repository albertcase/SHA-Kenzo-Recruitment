<?php
namespace WechatBundle;

use Core\Controller;

class CurioController extends Controller {

    public function callbackAction() {
        $request = $this->request;
        if ($url = $request->getSourcetUrl()) {
            $fields = array(
                'openid' => array('notnull', '120'),
            );
            $request->validation($fields);
            $userAPI = new \Lib\UserAPI();
            $openid = $request->query->get('openid');
            $user = $userAPI->userLogin($openid);
            if(!$user) {
                $userAPI->userRegister($openid);
            }
            $this->redirect($url);
        } else {
            $this->statusPrint('error');
        }
    }

    public function receiveUserInfoAction() {
        $data = file_get_contents("php://input");
        $data = json_decode($data);
        if($data->code = 200) {
            $DatabaseAPI = new \Lib\DatabaseAPI();
            $DatabaseAPI->updateUser($data->data);
        } else {
            $this->statusPrint('error');
        }
    }

}
