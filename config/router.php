<?php

$routers = array();
$routers['/wechat/oauth2'] = array('WechatBundle\Wechat', 'oauth');
$routers['/wechat/callback'] = array('WechatBundle\Wechat', 'callback');
$routers['/wechat/curio/callback'] = array('WechatBundle\Curio', 'callback');
$routers['/wechat/curio/receive'] = array('WechatBundle\Curio', 'receiveUserInfo');
$routers['/wechat/ws/jssdk/config/webservice'] = array('WechatBundle\WebService', 'jssdkConfigWebService');
$routers['/wechat/ws/jssdk/config/js'] = array('WechatBundle\WebService', 'jssdkConfigJs');
$routers['/ajax/post'] = array('CampaignBundle\Api', 'form');
$routers['/'] = array('CampaignBundle\Page', 'index');
$routers['/clear'] = array('CampaignBundle\Page', 'clearCookie');
$routers['/login'] = array('CampaignBundle\Page', 'login');
$routers['/api/submit'] = array('CampaignBundle\Api', 'submit');
$routers['/api/gift'] = array('CampaignBundle\Api', 'gift');
$routers['/api/islogin'] = array('CampaignBundle\Api', 'islogin');
$routers['/api/lottery'] = array('CampaignBundle\Api', 'lottery');
$routers['/api/picturecode'] = array('CampaignBundle\Api', 'pictureCode');
$routers['/api/checkpicture'] = array('CampaignBundle\Api', 'checkPicture');
$routers['/api/phonecode'] = array('CampaignBundle\Api', 'phoneCode');
$routers['/api/checkphonecode'] = array('CampaignBundle\Api', 'heckPhoneCode');
$routers['/cleardata'] = array('CampaignBundle\Page', 'clearData');
//$routers['/runopenid'] = array('CampaignBundle\Page', 'runopenid');
