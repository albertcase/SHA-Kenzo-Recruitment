/*For join page
 * Inclue two function, one is load new qr for each person, another is show rule popup
 * */
;(function(){
    var controller = function(){
        //each answer has relative score
        this.questionScore = {
            q1:10,
            q2:[10,10,20],
            q3:12, //用秒来计时，时间不一样，对应的分值也不同,默认分值是12
            q4:[10,10,20],
            q5:[10,10,20]
        };
        //default deleted option
        this.selectedOption = {
            q1:0,
            q1Text:'test',
            q2:0,
            q3:0,
            q4:0,
            q5:0
        };

        var ArrLength=48;
        this.loadingImg = [];
        var newstring1 = '';
        for(var i=1;i<ArrLength;i=i+2){
            if(i<10){
                newstring1='/src/images/'+'loading/loading_0000'+i+'.jpg';
            }else if(i>9 && i<100){
                newstring1='/src/images/'+'loading/loading_000'+i+'.jpg';
            }else{
                newstring1='/src/images/'+'loading/loading_00'+i+'.jpg';
            }
            this.loadingImg.push(newstring1);
        };

    };
    //init
    controller.prototype.init = function(){
        var self = this;

        var baseurl = ''+'/src/images/';
        var imagesArray = [
            baseurl + 'logo.png',
            baseurl + 'bg.jpg',
            baseurl + 'p1-t1.png',
            baseurl + 'p2-1.jpg',
            baseurl + 'question-1.jpg',
            baseurl + 'q2-a.png',
            baseurl + 'q2-b.png',
            baseurl + 'q2-c.png',
            baseurl + 'q2-content.png',
            baseurl + 'question-3.jpg',
            baseurl + 'upload-bg.jpg',
            baseurl + 'q4-a.png',
            baseurl + 'q4-b.png',
            baseurl + 'q4-c.png',
            baseurl + 'q4-content.png',
            baseurl + 'q5-a.png',
            baseurl + 'q5-b.png',
            baseurl + 'q5-c.png',
            baseurl + 'q5-content.png',
            baseurl + 'btn-upload.png',
            baseurl + 'image-overlay.png',
            baseurl + 'bg-3.jpg',
            baseurl + 'final-share.png',
            baseurl + 'tips-upload.png',
        ];
        imagesArray = imagesArray.concat(self.loadingImg);
        var i = 0,j=0;
        new preLoader(imagesArray, {
            onProgress: function(){
                i++;
                //var progress = parseInt(i/imagesArray.length*100);
                //console.log(progress);
                //$('.preload .v-content').html(''+progress+'%');
                //console.log(i+'i');
            },
            onComplete: function(){
                $('.preload').remove();
                $('.wrapper').addClass('fadein');

                Api.getlistbyid({id:Common.getParameterByName('id')},function(data){
                    if(data.status==1){
                        //weixinshare
                        weixinshare({
                            title1: '看我CEO测试高达'+data.msg.total+'分，实力碾压你的双商！',
                            des: '用实力让情怀落地，用分数为自己说话',
                            link: window.location.href,
                            img: window.location.origin+'/src/images/share.jpg'
                        });
                        $('.upload-wrap img').attr('src',data.msg.image);
                        Common.gotoPin(0);
                    }else{
                        Common.alertBox.add(data.msg);
                    }
                });




                //bind events
                self.bindEvent();

                //set all img element width
                for(var k=0;k<document.getElementsByTagName('img').length;k++){
                    document.getElementsByTagName('img')[k].style.width = document.getElementsByTagName('img')[k].naturalWidth / 100 + 'rem';
                    document.getElementsByTagName('img')[k].style.height = document.getElementsByTagName('img')[k].naturalHeight / 100 + 'rem';
                };
            }
        });





    };

    //bind Events
    controller.prototype.bindEvent = function(){
        var self = this;


        //    btn-share
        $('.btn-share').on('touchstart',function(){
            $('.share-pop').addClass('show');
        });
        //share-pop
        $('.share-pop').on('touchstart',function(){
            $('.share-pop').removeClass('show');
        });
        //排行榜
        $('.btn-scorelists').on('touchstart',function(){
            Common.gotoPin(1);
            Api.isLogin(function(data){
                if(data.info){
                    $('#form-contact').addClass('hasinfo');
                }
            });
            //    get ranklist
            Api.rankList(function(data){
                if(data.status==1){
                    var listHtml = '';
                    for(var z=0;z<data.list.length;z++){
                        listHtml = listHtml+'<li class="item">'+
                            '<span class="num">'+(z+1)+'/</span>'+
                            '<span class="name">'+data.list[z].nickname+'</span>'+
                            '<span class="score">'+data.list[z].total+'</span>'+
                            '</li>';
                    }
                    $('.result-lists').html(listHtml);
                }else{
                    Common.alertBox.add(data.msg);
                }
            });
        });

        //    submit form
        $('#form-contact .btn-submit').on('touchstart', function(){
            if($('#input-name').val() && $('#input-mobile').val()){
                Api.submitInfo({
                    name:$('#input-name').val(),
                    info:$('#input-mobile').val()
                },function(data){
                    if(data.status==1){
                        //Common.alertBox.add('提交成功');
                        $('#form-contact').addClass('hasinfo');

                    }else{
                        Common.alertBox.add(data.msg);
                    }
                });
            }else{
                Common.alertBox.add('请完善表单');
            }
        });


        //    close the ranklist page
        $('#pin-result-lists .btn-close').on('touchstart',function(){
            Common.gotoPin(0);
        });

        //    play again
        $('#pin-upload .btn-playagain').on('touchstart',function(){
            window.location.href = location.origin;
        });

    };

        $(document).ready(function(){
//    show form
            var exchange = new controller();
            exchange.init();

        });

})();



