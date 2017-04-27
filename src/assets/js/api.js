/*All the api collection*/
Api = {
    //is fill form
    isFillForm:function(callback){
        Common.msgBox.add('loading...');
        $.ajax({
            url:'/api/islogin',
            type:'POST',
            dataType:'json',
            success:function(data){
                Common.msgBox.remove();
                return callback(data);
                //status=1 有库存
            }
        });

        //return callback({
        //    status:0,
        //    msg:'fillform'
        //})


    },

    isLuckyDraw:function(callback){
        //Common.msgBox.add('loading...');
        Common.msgBox.add('抽奖中...');
        $.ajax({
            url:'/api/lottery',
            type:'POST',
            dataType:'json',
            success:function(data){
                var aaa = setTimeout(function(){

                    Common.msgBox.remove();
                    clearTimeout(aaa);
                    return callback(data);
                },3000);

                //status=1 有库存
            }
        });

        //return callback({
        //    status:1,
        //    msg:'zhognjiang'
        //})


    },
    //submit form
    // name  info
///api/submit    name mobile province city area address
    submitInfo:function(obj,callback){
        Common.msgBox.add('loading...');
        $.ajax({
            url:'/api/submit',
            type:'POST',
            dataType:'json',
            data:obj,
            success:function(data){
                Common.msgBox.remove();
                return callback(data);
            }
        });

        //return callback({
        //    status:1,
        //    msg:'提交成功'
        //});


    },


};