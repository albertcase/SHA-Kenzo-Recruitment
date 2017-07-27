# Captcher
Captcha Generator (PHP + GD)

## Basic usage
```php
use CodeRider\Captcher

$captcha = new CaptchaGenerator(200, 50);
$captchaImage = $captcha->generate();
$captchaText = $captcha->getCaptchaText();
```
#### Output:
*$captchaImage* output:  
![alt tag](https://raw.githubusercontent.com/coderiderpl/Captcher/master/example/captcha-example.jpg)

*$captchaText* output:  
ERZ2

## Custom options
### Background color
##### (Default: rgb(255, 255, 255))
``` php
$captcha->setBackgroundColor([146,178,135]);
```
### Text color
##### (Default: rgb(0, 0, 0))
``` php
$captcha->setTextColor([146,178,135]);
```
### Horizontal lines
##### (Default: true)
``` php
$captcha->setHorizontalLines(false);
```
### Vertical lines
##### (Default: true)
``` php
$captcha->setVerticalLines(false);
```
### Dots (on backgroud)
##### (Default: true)
``` php
$captcha->setDots(false);
```
### Text length
##### (Default: 4)
###### Notice: Fit text length to image width!
``` php
$captcha->setLettersAmount(5);
```
### Text length
##### (Default: 4)
###### Notice: Case sensitive!
``` php
$captcha->setAvailableCharacters('abc012');
```




