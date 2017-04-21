/*For join page
 * Inclue two function, one is load new qr for each person, another is show rule popup
 * */
;(function(){
    var controller = function(){

    };
    //init
    controller.prototype.init = function(){
        var self = this;

        self.bindEvent();
    };

    //bind Events
    controller.prototype.bindEvent = function(){
        var self = this;
    //    receive the prize
        $('.btn-getprize').on('touchstart',function(){
            Common.gotoPin(1);
        });

    //    submit the form
        $('.btn-submit').on('touchstart',function(){
            Api.submitInfo({

            },function(data){
                if(data.status==1){
                    Common.gotoPin(2);
                }else{
                    alert(data.msg);
                }
            });
        });

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
            inputAddress = document.getElementById('input-address');

        if(!inputName.value){
            Common.errorMsg.add(inputName.parentElement,'请填写姓名');
            validate = false;
        }else{
            Common.errorMsg.remove(inputName.parentElement);
        };

        if(!inputMobile.value){
            Common.errorMsg.add(inputMobile.parentElement,'手机号码不能为空');
            validate = false;
        }else{
            var reg=/^1\d{10}$/;
            if(!(reg.test(inputMobile.value))){
                validate = false;
                Common.errorMsg.add(inputMobile.parentElement,'手机号格式错误，请重新输入');
            }else{
                Common.errorMsg.remove(inputMobile.parentElement);
            }
        }

        if(!inputAddress.value){
            Common.errorMsg.add(inputAddress.parentElement,'请填写地址');
            validate = false;
        }else{
            Common.errorMsg.remove(inputAddress.parentElement);
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