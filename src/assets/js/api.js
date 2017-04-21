/*All the api collection*/
Api = {

    isLogin:function(callback){
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
        //    status:1,
        //    avatar:'/src/images/qr-1.png',
        //    score:'100'
        //})


    },
    //
    //is fill form
    isFillForm:function(callback){
        Common.msgBox.add('loading...');
        //$.ajax({
        //    url:'/api/answer',
        //    type:'POST',
        //    data:obj,
        //    dataType:'json',
        //    success:function(data){
        //        Common.msgBox.remove();
        //        return callback(data);
        //        //status=1 有库存
        //    }
        //});

        return callback({
            status:1,
        })


    },

    isLuckyDraw:function(callback){
        Common.msgBox.add('loading...');
        //$.ajax({
        //    url:'/api/answer',
        //    type:'POST',
        //    data:obj,
        //    dataType:'json',
        //    success:function(data){
        //        Common.msgBox.remove();
        //        return callback(data);
        //        //status=1 有库存
        //    }
        //});

        return callback({
            status:1,
        })


    },

    //rank list
    rankList:function(callback){
        Common.msgBox.add('loading...');
        $.ajax({
            url:'/api/list',
            type:'POST',
            dataType:'json',
            success:function(data){
                Common.msgBox.remove();
                return callback(data);
            }
        });

        //return callback({
        //    status:1,
        //    avatar:'/src/images/qr-1.png',
        //    score:'100'
        //});


    },
    //submit form
    // name  info
    submitInfo:function(obj,callback){
        Common.msgBox.add('loading...');
        //$.ajax({
        //    url:'/api/submit',
        //    type:'POST',
        //    dataType:'json',
        //    data:obj,
        //    success:function(data){
        //        Common.msgBox.remove();
        //        return callback(data);
        //    }
        //});

        return callback({
            status:1,
            msg:'提交成功'
        });


    },
    // id
    getlistbyid:function(obj,callback){
        Common.msgBox.add('loading...');
        $.ajax({
            url:'/api/getlistbyid',
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
        //    avatar:'/src/images/qr-1.png',
        //    score:'100'
        //});


    },
///api/getlistbyid


};