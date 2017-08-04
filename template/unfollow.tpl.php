<!DOCTYPE html>
<html>
<head lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>KENZO全新果冻霜礼赠</title>
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="x5-fullscreen" content="true">
    <meta name="full-screen" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="../src/dist/css/style.css"/>
    <script src="http://kenzowechat.samesamechina.com/weixin/jssdkforsite"></script>
    <script>
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "https://hm.baidu.com/hm.js?f60c5af227048e276b4e4d768cdfb959";
      var s = document.getElementsByTagName("script")[0];
      s.parentNode.insertBefore(hm, s);
    })();
    </script>
    <script src="../src/assets/js/lib/zepto.min.js"></script>
    <script src="../src/assets/js/rem.js"></script>
    <script src="../src/assets/js/common.js"></script>
    <script src="../src/assets/js/wxshare.js"></script>
</head>
<body class="page-home">
    <div id="orientLayer" class="mod-orient-layer">
        <div class="mod-orient-layer__content">
            <i class="icon mod-orient-layer__icon-orient"></i>
            <div class="mod-orient-layer__desc">请在解锁模式下使用竖屏浏览</div>
        </div>
    </div>
    <!--main content-->
    <!-- 未关注 -->
    <div class="wrapper animate fade">
        <!-- sometimes z-index is larger than border-frame, sometimes is lower-->
        <!-- z-index is middle-->
        <div class="border-frame">
            <div class="bf bf-1"></div>
            <div class="bf bf-2"></div>
            <div class="bf bf-3"></div>
        </div>
        <div class="logo">
            <img src="../src/dist/images/logo.png" alt="kenzo"/>
        </div>
        <!-- z-index is low-->
        <div class="container">
            <div class="pin pin-1 current" id="pin-follow">
                <div class="qrcode">
                    <img src="../src/dist/images/qrcode.jpg" alt="kenzo"/>
                </div>
                <div class="qrcode-des">
                    <img src="../src/dist/images/qr-des.png" alt="kenzo"/>
                </div>
                <div class="foreground">
                    <img src="../src/dist/images/foreground-1.png" alt="kenzo"/>
                </div>
            </div>
        </div>
    </div>
    <!-- z-index is high-->
    <div class="popup"></div>
</body>
</html>