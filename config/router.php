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
$routers['/result'] = array('CampaignBundle\Page', 'result');
$routers['/reservation'] = array('CampaignBundle\Page', 'reservation');
$routers['/login'] = array('CampaignBundle\Page', 'login');
$routers['/api/answer'] = array('CampaignBundle\Api', 'answer');
$routers['/api/list'] = array('CampaignBundle\Api', 'list');
$routers['/api/getlistbyid'] = array('CampaignBundle\Api', 'load');
$routers['/api/submit'] = array('CampaignBundle\Api', 'submit');
$routers['/api/band'] = array('CampaignBundle\Page', 'band');
$routers['/api/islogin'] = array('CampaignBundle\Api', 'islogin');
$routers['/api/clearmake'] = array('CampaignBundle\Page', 'clearmake');
$routers['/jssdk'] = array('CampaignBundle\Page', 'jssdkConfigJs');
$routers['/rank'] = array('CampaignBundle\Page', 'rank');
$routers['/cleardata'] = array('CampaignBundle\Page', 'clearData');