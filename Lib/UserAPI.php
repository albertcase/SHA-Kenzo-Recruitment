<?php
namespace Lib;

use Core\Response;

class UserAPI extends Base {

  private $_db;

  public function __construct() {
    $this->_db = new DatabaseAPI();
  }

  public function userLoad($openid = 0){
    if($openid) {
      if($user = $this->_db->findUserByOpenid($openid)) {
        return $user;
      } else {
        return FALSE;
      }
    } else {
      if($_user = $this->isUserLogin()){
        $data = new \stdClass();
        $data->uid = $_user->uid;
        $data->openid = $_user->openid;
        // if($re = $this->_db->findInfoByUid($_user->uid)) {
        //   $data->info = $re;
        // }
        return $data;
      } else {
        return (object) array('uid' => '0', 'openid' => '0');
      }
    }
  }

  public function userLogin($openid){
    $user = $this->_db->findUserByOpenid($openid);
    if($user) {
      return $this->userLoginFinalize($user);
    }
    return FALSE;
  }

  public function isUserLogin() {
    // $r = new \stdClass();
    // $r->uid = '1';
    // $r->openid = 'asf';
    // return $r;
    if(USER_STORAGE == 'COOKIE') {
      if(isset($_COOKIE['_user'])) {
        return $this->decodeUser($_COOKIE['_user']);
      }
    } else {
      if(isset($_SESSION['_user'])) {
        return json_decode($_SESSION['_user']);
      }
    }
    return FALSE;
  }

  public function userLoginFinalize($user) {
    if(USER_STORAGE == 'COOKIE') {
      setcookie('_user', $this->encodeUser($user), time() + 3600 * 24 * 100, '/');
    } else {
      $_SESSION['_user'] = json_encode($user);
    }
    return $user;
  }

  public function userRegister($openid){
    $userinfo = new \stdClass();
    $userinfo->openid = $openid;
    $user = $this->_db->insertUser($userinfo);
    return $this->userLoginFinalize($user);
  }

  public function oauthAction($scope, $redirect_uri) {
    $wechatUserAPI = new \Lib\WechatAPI();
    $param['redirect_uri'] = $redirect_uri;
    $callback = BASE_URL . CALLBACK . '?' . http_build_query($param);
    $url = $wechatUserAPI->getAuthorizeUrl(APPID, $callback, $scope);
    $response = new Response();
    $response->redirect($url);  
  }

  public function encodeUser($data) {
    $data = base64_encode($this->aes128_cbc_encrypt(ENCRYPT_KEY, json_encode($data), ENCRYPT_IV));
    return $data;
  }

  public function decodeUser($string) {
    $string = base64_decode($string, TRUE);
    $data = $this->aes128_cbc_decrypt(ENCRYPT_KEY, $string, ENCRYPT_IV);
    $user = json_decode($data);
    return $user;
  }

  public function aes128_cbc_encrypt($key, $data, $iv) {
    if(16 !== strlen($key)) $key = hash('MD5', $key, true);
    if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
    $padding = 16 - (strlen($data) % 16);
    $data .= str_repeat(chr($padding), $padding);
    return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
  }

  public function aes128_cbc_decrypt($key, $data, $iv) {
    if(16 !== strlen($key)) $key = hash('MD5', $key, true);
    if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
    $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
    $padding = ord($data[strlen($data) - 1]);
    return substr($data, 0, -$padding);
  }

}