/*For join page
 * Inclue two function, one is load new qr for each person, another is show rule popup
 * */
;(function(){
    var controller = function(){
        //get userflow status from backend

        //var userInfo = {
        //    isOld: false, /*是否是老用户*/
        //    isSubmit: false, /*是否提交了用户详细信息表单*/
        //    isGift: false, /*是否领取了小样*/
        //    isLuckyDraw: false /*是否抽奖*/
        //};

        this.user = userInfo;
        this.isTransformedOld = userInfo.isOld; //For new follow user, if operate the gift page, then transform Old user and display old page view
    };
    //init
    controller.prototype.init = function(){
        var self = this;

        var timeStart = 0,
            step= 5,
            isTrueNext = false,
            isFalseNext = false;
        var loadingAni = setInterval(function(){
            if(timeStart>100){
                isFalseNext = true;
                if(isTrueNext){
                    self.startUp();
                }
                clearInterval(loadingAni);
                return;
            };
            if(timeStart==step){
                $('.animate-flower').addClass('fadenow');
            }
            $('.loading-num .num').html(timeStart);
            timeStart += step;
        },200);

        var baseurl = ''+'/src/dist/images/';
        var imagesArray = [
            baseurl + 'logo.png',
            baseurl + 'ani-1.png',
            baseurl + 'ani-2.png',
            baseurl + 'ani-3.png',
            baseurl + 'ani-5.png',
            baseurl + 'bg.jpg',
            baseurl + 'btn.png',
            baseurl + 'foreground-1.png',
            baseurl + 'gift-flower.png',
            baseurl + 'guide-share.png',
            baseurl + 'landing-1.png',
            baseurl + 'pop-bg.png',
            baseurl + 'preload-bg.jpg',
            baseurl + 'preload-flower.jpg'
        ];

        var i = 0,j= 0;
        new preLoader(imagesArray, {
            onProgress: function(){
                i++;
                //var progress = parseInt(i/imagesArray.length*100);
                //console.log(progress);
                //$('.preload .v-content').html(''+progress+'%');
                //console.log(i+'i');
            },
            onComplete: function(){
                isTrueNext  = true;
                if(isFalseNext){
                    self.startUp();
                }

            }
        });


    };

    controller.prototype.startUp = function(){
        var self = this;
        $('.preload').remove();
        $('.wrapper').addClass('fade');

        /* if the isOld is true and isLuckyDraw is true, directly go to the luckydraw result page */
        if(self.user.isOld && self.user.isLuckyDraw){
            Common.gotoPin(2); /*directly go to the luckydraw result page*/
        }else{
            Common.gotoPin(0); // landing page
            if(self.user.isOld){
                self.showLandingPage(2);
            }else{
                self.showLandingPage(1);
            }
        }
        //if(self.user.isOld){
        //    if(self.user.isLuckyDraw){
        //        Common.gotoPin(2);
        //    }else{
        //        Common.gotoPin(0);
        //    }
        //}

        //console.log(self.hasShared);
        self.bindEvent();
        self.showAllProvince();

        //test
        Common.hashRoute();
    };

    controller.prototype.showLandingPage = function(page){
        if(page == 1){
            $('.btn-luckydraw').text('即刻领取体验装');
            $('.limit-quantity').removeClass('hide');
        }else if(page == 2){
            $('.btn-luckydraw').text('即刻赢取礼赠');
            $('.limit-quantity').addClass('hide');
        }
    };

    //bind Events
    controller.prototype.bindEvent = function(){
        var self = this;
        //show and hide terms pop
            //close terms popup
        $('body').on('touchstart','.btn-close',function(){
            $('.terms-pop').removeClass('show');
        });
        //    show terms pop
        $('.terms-link').on('touchstart',function(){
            /**/
            var termContent = [
                {
                    time:'2017年X月X日至2017年X月X日',
                    condition:'活动期间，首次关注KenzoParfums凯卓官方微信的<br>用户即可参与申领，每个微信ID仅限申领一次，<br>奖品限量5000份。每天份额限量，详情请见活动主页<br>（先到先得）',
                    prize:'奖品为KENZO舒缓白莲清爽保湿霜体验装（2ml）<br>根据用户填写的邮寄地址在中奖后的30个工作日内寄送'
                },
                {
                    time:'2017年X月X日至2017年X月X日',
                    condition:'活动期间，关注KenzoParfums凯卓官方微信的<br>用户将活动分享给好友，即可参与抽奖（随机抽取）<br>每个微信ID仅限中奖一次，奖品限量100份',
                    prize:'奖品为KENZO舒缓白莲清爽保湿霜正装（50ml）<br>根据用户填写的邮寄地址在中奖后的30个工作日内寄送'
                }
            ];
            if(self.isTransformedOld){
                $('.activity-time').html(termContent[1].time);
                $('.activity-requirement').html(termContent[1].condition);
                $('.activity-prize').html(termContent[1].prize);
            }else{
                $('.activity-time').html(termContent[0].time);
                $('.activity-requirement').html(termContent[0].condition);
                $('.activity-prize').html(termContent[0].prize);
            }
            $('.terms-pop').addClass('show');

        });

        /*
        * If isTransformedOld is true, show share popup
        * If isTransformedOld is false and not fill form, you need fill form first
        * If isTransformedOld is false and filled form, you directly go result page
        * */
        $('.btn-luckydraw').on('touchstart',function(){
            if(self.isTransformedOld){
                $('.share-popup').addClass('show');
            }else{
                if(self.user.isSubmit){
                    Common.gotoPin(2); //go result page
                }else{
                    Common.gotoPin(1); //go fill form page
                }
            }
        });

        //    submit the form
        $('.btn-submit').on('touchstart',function(){
            if(self.validateForm()){
                //name mobile province city area address
                var inputNameVal = $('#input-name').val(),
                    inputMobileVal = $('#input-mobile').val(),
                    inputAddressVal = $('#input-address').val(),
                    selectProvinceVal = $('#select-province').val(),
                    selectCityVal = $('#select-city').val(),
                    selectDistrictVal = $('#select-district').val();
                Api.submitInfo({
                    name:inputNameVal,
                    mobile:inputMobileVal,
                    province:selectProvinceVal,
                    city:selectCityVal,
                    area:selectDistrictVal,
                    address:inputAddressVal
                },function(data){
                    if(data.status==1){
                        Common.gotoPin(2);
                        self.prizeResult();
                    }else{
                        alert(data.msg);
                    }
                });
            }

        });

    //    switch the province
        var curProvinceIndex = 0;
        $('#select-province').on('change',function(){
            curProvinceIndex = document.getElementById('select-province').selectedIndex;
            self.showCity(curProvinceIndex);
        });

        $('#select-city').on('change',function(){
            var curCityIndex = document.getElementById('select-city').selectedIndex;
            self.showDistrict(curProvinceIndex,curCityIndex);
        });

        $('#select-district').on('change',function(){
            var districtInputEle = $('#input-text-district'),
                districtSelectEle = $('#select-district');
            var curCityIndex = document.getElementById('select-district').selectedIndex;
            districtInputEle.val(districtSelectEle.val());
        });


    //    share function
        weixinshare({
            title1: 'KENZO关注有礼 | 睡美人面膜免费申领 ',
            des: '和“好肌友”一起领取睡美人悦肤礼赠吧！',
            link: window.location.origin,
            img: window.location.origin+'/src/dist/images/share.jpg'
        },function(){
            self.shareSuccess();

        });

    //    imitate share function on pc
        $('.share-popup .guide-share').on('touchstart',function(){
            self.shareSuccess();
        });

        self.getValidateCode();

        //switch validate code
        $('.validate-code').on('touchstart', function(){
            self.getValidateCode();
        });

        //Get message validate code
        $('.btn-get-msg-code').on('touchstart', function(){
            Api.checkValidateCode({
                picture:$('#input-validate-code').val()
            },function(data){
                console.log(data);
            });
        });

    };

    controller.prototype.getValidateCode = function(){
        Api.getValidateCode(function(data){
            console.log(data);
            if(data.status==1){
                $('.validate-code-img').html('<img src="data:image/jpeg;base64,'+data.picture+'" />');
                //var codeImg = new Image();
                //codeImg.onload = function(){
                //
                //}
                //codeImg.src = data.picture;
            }
        })
    };

    //share success
    controller.prototype.shareSuccess = function(){
        var self = this;
        $('.share-popup').removeClass('show');
        Api.isFillForm(function (data) {
            //if filled, go lucky draw page
            //if not,fill form first
            if(data.status == 1){
                Common.gotoPin(2);
                self.prizeResult();
            }else{
                Common.gotoPin(1);
            }
        })
    };

    //show the prize result, if prize, show prize msg, if not, show sorry msg
    controller.prototype.prizeResult = function(){
        Common.gotoPin(2);
        $('.prize-item').removeClass('show');
        Api.isLuckyDraw(function(result){
            //self.prizeResult(result.status,result.msg);
            if(result.status==1){
                Cookies.set('getPrize',1);
                //    get prize
                $('.prize-yes').addClass('show');
                $('.prize-no').removeClass('show');
            }else if(result.status==2){
                Cookies.set('getPrize',2);
                $('.prize-yes').removeClass('show');
                $('.prize-no').addClass('show');
            }else{
                Common.alertBox.add(result.msg);
            }
        });

    };

    //province city and district
    controller.prototype.showAllProvince = function(){
        var self = this;
        //    list all province
        var provinces = '';
        var provinceSelectEle = $('#select-province'),
            provinceInputEle = $('#input-text-province');
        region.forEach(function(item){
            provinces = provinces+'<option value="'+item.name+'">'+item.name+'</option>';
        });
        provinceSelectEle.html(provinces);
        provinceInputEle.val(provinceSelectEle.val());
        self.showCity(0);
        self.showDistrict(0,0);
    };

    controller.prototype.showCity = function(curProvinceId){
        var self = this;
        //    show current cities
        var cities='';
        var provinceSelectEle = $('#select-province'),
            provinceInputEle = $('#input-text-province'),
            citySelectEle = $('#select-city'),
            cityInputEle = $('#input-text-city');
        var cityJson = region[curProvinceId].city;
        cityJson.forEach(function(item,index){
            cities = cities + '<option data-id="'+index+'" value="'+item.name+'">'+item.name+'</option>';
        });
        citySelectEle.html(cities);
        provinceInputEle.val(provinceSelectEle.val());
        cityInputEle.val(citySelectEle.val());
        self.showDistrict(curProvinceId,0);
    };

    controller.prototype.showDistrict = function(curProvinceId,curCityId){
        var self = this;
        var districtSelectEle = $('#select-district'),
            districtInputEle = $('#input-text-district'),
            citySelectEle = $('#select-city'),
            cityInputEle = $('#input-text-city');
        //    show current districts
        var districts = '';
        var districtJson = region[curProvinceId].city[curCityId].area;
        districtJson.forEach(function(item,index){
            districts = districts + '<option data-id="'+index+'" value="'+item+'">'+item+'</option>';
        });
        cityInputEle.val(citySelectEle.val());
        districtSelectEle.html(districts);
        districtInputEle.val(districtSelectEle.val());
    };

    //validation the form
    controller.prototype.validateForm = function(){
        var self = this;
        var validate = true,
            inputName = document.getElementById('input-name'),
            inputMobile = document.getElementById('input-mobile'),
            inputAddress = document.getElementById('input-address'),
            selectProvince = document.getElementById('select-province'),
            selectCity = document.getElementById('select-city'),
            selectDistrict = document.getElementById('select-district');

        if(!inputName.value){
            Common.errorMsgBox.add('请填写姓名');
            validate = false;
        };

        if(!inputMobile.value){
            Common.errorMsgBox.add('手机号码不能为空');
            //Common.errorMsg.add(inputMobile.parentElement,'手机号码不能为空');
            validate = false;
        }else{
            var reg=/^1\d{10}$/;
            if(!(reg.test(inputMobile.value))){
                validate = false;
                Common.errorMsgBox.add('手机号格式错误，请重新输入');
                //Common.errorMsg.add(inputMobile.parentElement,'手机号格式错误，请重新输入');
            }else{
                //Common.errorMsg.remove(inputMobile.parentElement);
            }
        }

        if(!selectProvince.value || selectProvince.value == '省份'){
            //Common.errorMsg.add(selectProvince.parentElement,'请选择省份');
            Common.errorMsgBox.add('请选择省份');
            validate = false;
        }else{
            //Common.errorMsg.remove(selectProvince.parentElement);
        };

        if(!selectCity.value || selectCity.value == '城市' || !selectDistrict.value || selectDistrict.value == '区县' ){
            //Common.errorMsg.add(selectCity.parentElement.parentElement,'请选择城市和区县');
            Common.errorMsgBox.add('请选择城市和区县');
            validate = false;
        }else{
            //Common.errorMsg.remove(selectCity.parentElement);
        };

        if(!inputAddress.value){
            //Common.errorMsg.add(inputAddress.parentElement,'请填写地址');
            Common.errorMsgBox.add('请填写地址');
            validate = false;
        }else{
            //Common.errorMsg.remove(inputAddress.parentElement);
        };

        if(validate){
            return true;
        }
        return false;
    };


    $(document).ready(function(){
//    show form
        var newFollow = new controller();
        newFollow.startUp();

    });

})();