/*For join page
 * Inclue two function, one is load new qr for each person, another is show rule popup
 * */
;(function(){
    var controller = function(){
        this.hasShared = true;
    };
    //init
    controller.prototype.init = function(){
        var self = this;

        var timeStart = 0,
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
            if(timeStart==2){
                $('.animate-flower').addClass('fadenow');
            }
            $('.loading-num .num').html(timeStart);
            timeStart++;
        },100);

        var baseurl = ''+'/src/dist/images/';
        var imagesArray = [
            baseurl + 'logo.png'
        ];

        imagesArray = imagesArray.concat(self.loadingImg);
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
        Common.gotoPin(0);
        self.bindEvent();
        self.showAllProvince();
    };
    //bind Events
    controller.prototype.bindEvent = function(){
        var self = this;
        //    receive the prize
        $('.btn-luckydraw').on('touchstart',function(){
            //Common.gotoPin(1);
            //if user has shared the link, go next page,
            //if not, show pop to guide user share
            if(self.hasShared){
                Api.isFillForm(function (data) {
                    //if filled, go lucky draw page
                    //if not,fill form first
                    if(data.status == 1){
                        Common.gotoPin(2);
                        Api.isLuckyDraw(function(data){
                            if(data.status==1){
                                //    中奖
                            }else{
                                // 没有中奖
                            }
                        })
                    }else{
                        Common.gotoPin(1);
                    }
                })
            }else{
                console.log('show share pop');
            }
        });

        //    submit the form
        $('.btn-submit').on('touchstart',function(){
            if(self.validateForm()){
                var inputNameVal = $('input-name').val(),
                    inputMobileVal = $('input-mobile').val(),
                    inputAddressVal = $('input-address').val(),
                    selectProvinceVal = $('select-province').val(),
                    selectCityVal = $('select-city').val(),
                    selectDistrictVal = $('select-district').val();
                Api.submitInfo({
                    name:inputNameVal,
                    mobile:inputMobileVal,
                    province:selectProvinceVal,
                    city:selectCityVal,
                    district:selectDistrictVal,
                    address:inputAddressVal
                },function(data){
                    if(data.status==1){
                        Common.gotoPin(2);
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

    };

    //province city and district
    controller.prototype.showAllProvince = function(){
        var self = this;
        //    list all province
        var provinces = '';
        var provinceSelectEle = $('#select-province'),
            provinceInputEle = $('#input-text-province');
        for(var i=0;i<region.length;i++){
            provinces = provinces + '<option value="'+region[i].name+'">'+region[i].name+'</option>';
        }
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
        for(var j=0;j<cityJson.length;j++){
            cities = cities + '<option data-id="'+j+'" value="'+cityJson[j].name+'">'+cityJson[j].name+'</option>';
        }
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
        for(var k=0;k<districtJson.length;k++){
            districts = districts + '<option data-id="'+k+'" value="'+districtJson[k]+'">'+districtJson[k]+'</option>';
        }
        districtSelectEle.html(districts);
        districtInputEle.val(districtSelectEle.val());
    };

    //load user info and fill it
    controller.prototype.userInfo = function(){
        var self = this;
        Api.isLogin(function(data){
            var imgAvatar = data.msg.headimgurl,
                score = data.msg.score,
                scoreProgress = parseInt(score) / 520 * 100 + '%';
            $('.avatar img').attr('src',imgAvatar);
            $('.stars .progress').css('width',scoreProgress);
            $('.total-score .num').html(score);

            var info = data.info;
            if(info){
                //    user info
                $('#input-name').val(info.name);
                $('#input-mobile').val(info.cellphone);
                $('#input-address').val(info.address);
            }

        });
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
        newFollow.init();

    });

})();