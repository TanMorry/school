
/*
* 注意：
* 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
* 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
* 3. 完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
*
* 如有问题请通过以下渠道反馈：
* 邮箱地址：weixin-open@qq.com
* 邮件主题：【微信JS-SDK反馈】具体问题
* 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
*/
$(function () {
//  var desc="";
//  if($("#yqm").val()!=null&&$("#yqm").val()!=""){
//   desc='我的邀请码是：'+ $("#yqm").val()+'，注册时请先输入哦。';
//  }
    $.ajax({
        url: "http://wechat.huipiaoxian.com/getticket",
        type: 'get',
        dataType: 'json',
        data: 'currentUrl=' + encodeURIComponent(location.href.split('#')[0]),

        error: function (XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest.status);
            //alert(XMLHttpRequest.readyState);
            //alert(textStatus);
        },
        success: function (data) {

            //alert(data.url);
            wx.config({
                debug: false,
                appId: 'wx6baa3f2b34f3163e',
                timestamp: data.timestamp,
                nonceStr: data.nonceStr,
                signature: data.signature,
                jsApiList: [
                    'checkJsApi',
                    'onMenuShareTimeline',
                    'onMenuShareAppMessage',
                    'onMenuShareQQ',
                    'onMenuShareWeibo',
                    'hideMenuItems',
                    'showMenuItems',
                    'hideAllNonBaseMenuItem',
                    'showAllNonBaseMenuItem',
                    'translateVoice',
                    'startRecord',
                    'stopRecord',
                    'onRecordEnd',
                    'playVoice',
                    'pauseVoice',
                    'stopVoice',
                    'uploadVoice',
                    'downloadVoice',
                    'chooseImage',
                    'previewImage',
                    'uploadImage',
                    'downloadImage',
                    'getNetworkType',
                    'openLocation',
                    'getLocation',
                    'hideOptionMenu',
                    'showOptionMenu',
                    'closeWindow',
                    'scanQRCode',
                    'chooseWXPay',
                    'openProductSpecificView',
                    'addCard',
                    'chooseCard',
                    'openCard'
                ]
            });



            wx.ready(function () {

                wx.onMenuShareAppMessage({
                    title: '曝力校区',
                    desc: '专注个性化家庭教育新方式，包括：定制亲子游，边玩边学；专为亲子量身制作的舞台演出；更专业的家庭教育专栏，每周与教育专家探讨家庭教育的机会；共享书城，让孩子体验到分享的乐趣。我们对孩子的用心，正如你的用心！。',
                    link: data.url,
                    imgUrl: 'http://wechat.huipiaoxian.com/invitation/images/logo.png',
                    trigger: function (res) {
                        // alert('用户点击发送给朋友');
                    },
                    success: function (res) {
//                      $(".cpopt").fadeOut();
//                      $('.cpopts').fadeIn().delay(3000).fadeOut(0);
                    },
                    cancel: function (res) {
                    },
                    fail: function (res) {
                    }
                });

                wx.onMenuShareTimeline({
                    title: '曝力校区',
                    desc: '专注个性化家庭教育新方式，包括：定制亲子游，边玩边学；专为亲子量身制作的舞台演出；更专业的家庭教育专栏，每周与教育专家探讨家庭教育的机会；共享书城，让孩子体验到分享的乐趣。我们对孩子的用心，正如你的用心！。',
                    link: data.url,
                    imgUrl: 'http://wechat.huipiaoxian.com/invitation/images/logo.png',
                    trigger: function (res) {
                    },
                    success: function (res) {
//                      $(".cpopt").fadeOut();
//                      $('.cpopts').fadeIn().delay(3000).fadeOut(0);
                    },
                    cancel: function (res) {
                    },
                    fail: function (res) {
                    }
                });

                wx.onMenuShareQQ({
                    title: '曝力校区',
                    desc: '专注个性化家庭教育新方式，包括：定制亲子游，边玩边学；专为亲子量身制作的舞台演出；更专业的家庭教育专栏，每周与教育专家探讨家庭教育的机会；共享书城，让孩子体验到分享的乐趣。我们对孩子的用心，正如你的用心！。',
                    link: data.url,
                    imgUrl: 'http://wechat.huipiaoxian.com/invitation/images/logo.png',
                    trigger: function (res) {
                    },
                    success: function (res) {
//                      $(".cpopt").fadeOut();
//                      $('.cpopts').fadeIn().delay(3000).fadeOut(0);
                    },
                    cancel: function (res) {
                        $(".cancel").click(function () {
                        })

                    },
                    fail: function (res) {
                    }
                });

                wx.onMenuShareWeibo({
                    title: '曝力校区',
                    desc: '专注个性化家庭教育新方式，包括：定制亲子游，边玩边学；专为亲子量身制作的舞台演出；更专业的家庭教育专栏，每周与教育专家探讨家庭教育的机会；共享书城，让孩子体验到分享的乐趣。我们对孩子的用心，正如你的用心！。',
                    link: data.url,
                    imgUrl: 'http://wechat.huipiaoxian.com/invitation/images/logo.png',
                    trigger: function (res) {
                    },
                    success: function (res) {
//                      $(".cpopt").fadeOut();
//                      $('.cpopts').fadeIn().delay(3000).fadeOut(0);
                    },
                    cancel: function (res) {
                    },
                    fail: function (res) {
                    }
                });
            })


        }
        }

    );

});

