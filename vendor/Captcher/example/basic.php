<?php

require_once('../src/Captcher.php');
use CodeRider\Captcher\Captcher;

$captcha = new Captcher(250, 50);
$captchaImage = $captcha->generate();
$captchaText = $captcha->getCaptchaText();


echo "<img src='data:image/jpeg;base64," . base64_encode($captchaImage) . "'>";

$_SESSION['captcha-protection'] = $captchaText;
echo $captchaText;
