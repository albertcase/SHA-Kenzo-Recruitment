<!DOCTYPE html>
<html>
<head lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>KENZO</title>
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="x5-fullscreen" content="true">
    <meta name="full-screen" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="../src/dist/css/style.css"/>
    <script src="../src/assets/js/rem.js"></script>
    <script src="../src/assets/js/lib/zepto.min.js"></script>
    <script src="../src/assets/js/common.js"></script>
    <script src="../src/assets/js/api.js"></script>
    <script src="../src/assets/js/wxshare.js"></script>
    <script src="../src/assets/js/newfollow.js"></script>
</head>
<body class="page-home">
<div id="orientLayer" class="mod-orient-layer">
    <div class="mod-orient-layer__content">
        <i class="icon mod-orient-layer__icon-orient"></i>
        <div class="mod-orient-layer__desc">请在解锁模式下使用竖屏浏览</div>
    </div>
</div>
<div class="loading-wrap">
    loading...
</div>
<!--main content-->
<!-- 未关注 -->
<div class="wrapper">
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
        <div class="pin pin-1 current" id="pin-landing">
            <div class="title">
                <img src="../src/dist/images/landing2-1.png" alt="kenzo"/>
            </div>
            <div class="msg">
                感悟白莲精粹的呵护
                安然悠享整夜青春修护
                云朵般的慕斯质地轻而柔软
                清晨梦醒时肌肤紧致、光滑
                ···身心亦是焕然一新
            </div>
            <div class="btn btn-getprize">即刻领取睡美人试用装</div>
            <div class="terms-link">*规则与条款</div>
            <div class="foreground">
                <img src="../src/dist/images/foreground-1.png" alt="kenzo"/>
            </div>
        </div>
        <!-- 填写表单选项-->
        <div class="pin pin-2" id="pin-fillform">
            <h3 class="title">
                *请确认您的邮寄信息填写无误
                以便我们为您更快寄出产品
            </h3>
            <form id="form-contact">
                <div class="form-information">
                    <div class="input-box input-box-name">
                        <input type="text" id="input-name" placeholder="姓名"/>
                    </div>
                    <div class="input-box input-box-mobile">
                        <input type="tel" maxlength="11" id="input-mobile" placeholder="电话"/>
                    </div>
                    <div class="input-box input-box-province">
                        <select name="province" id="input-province">
                            <option value="">省份</option>
                        </select>
                    </div>
                    <div class="input-box input-box-city-district">
                        <select name="city" id="input-city">
                            <option value="">城市</option>
                        </select>
                        <select name="district" id="input-district">
                            <option value="">区县</option>
                        </select>
                    </div>
                </div>
                <div class="btn btn-submit">提 交</div>
            </form>

        </div>
        <div class="pin pin-3" id="pin-gift">
            <p>
                「恭喜您」
                获得KENZO睡美人面膜试用装*
                （2ML）一份
                Miss K 将火速为您寄送礼品！
            </p>
            <div class="p3-2">
                <p class="name">* KENZO花颜舒柔夜间修护面膜</p>
                <div class="btn btn-getproduct">点击赢取睡美人面膜正装</div>
            </div>
        </div>
    </div>
</div>
<!-- z-index is high-->
<div class="popup"></div>
</body>
</html>
