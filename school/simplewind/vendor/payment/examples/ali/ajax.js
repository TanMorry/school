$(document).ready(function () {

    $.ajax({
        type: "get",
        url: "http://47.100.16.157:86/api/home/index/menu",
        success: function (data) { //成功后回调
            var tem = data.data
            console.log(tem)
            $.each(tem, function (key, val) {
                $(".menu-list").append(
                    '<a href="' + tem[key].href + '">' +
                    '<div>' + tem[key].name + '<div class="indexa"></div></div>' +
                    '</a>'
                )
            })
            var url = window.location.pathname
            url = url.substring(url.lastIndexOf('/') + 1, url.lastIndexOf('.'))
            if(url=="index"){
    	    		
    	    		$(".menu-list a:nth-child(1) div:nth-child(1)").addClass("cmen")
    	    	}
    	    	
    	    	if(url=="introduction"){
    	    		$(".menu-list a:nth-child(2) div:nth-child(1)").addClass("cmen")
    	    		
    	    	}
    	    	if(url=="coopn"){
    	    		$(".menu-list a:nth-child(3) div:nth-child(1)").addClass("cmen")
    	    		
    	    	}
    	    	if(url=="About"){
    	    		
    	    		$(".menu-list a:nth-child(1) div:nth-child(1)").addClass("cmen")
    	    	}
    	    	if(url=="wedetail"){
    	    		$(".menu-list a:nth-child(4) div:nth-child(1)").addClass("cmen")
    	    	}
        }
    })

    /*menu请求*/


    $.ajax({
        type: "get",
        url: "http://47.100.16.157:86/api/home/index/info_cate",
        success: function (data) {
            var tema = data.data.identity
            var temb = data.data.spell
            var temc = data.data.scale

            /*人数*/

            console.log(temc)
            $.each(temc, function (key, val) {
                $(".sfzsc").append(
                    '<input type="button" id="' + temc[key].id + '" class="cms"  value="' + temc[key].scale_name + '" />'
                )
            })
            var kcs = $(".sfzsc input:nth-child(1)").attr("id")
            $(".kc").text(kcs)

            $(".sfzsc input:nth-child(1)").addClass("cm amc")
            $(".sfzsc input[type=button]").on('click', function () {
                $(this).addClass("cm").siblings().removeClass("cm");
                if ($(".cms").hasClass("cm")) {
                    var vrec = $(this).attr("id")
                    $(".kc").text(vrec)
                }
            })

            /*时间*/

            $.each(temb, function (key, val) {
                $(".sfzsb").append(
                    '<input type="button" id="' + temb[key].id + '" class="cms"  value="' + temb[key].time + '" />'
                )
            })
            var kbs = $(".sfzsb input:nth-child(1)").attr("id")
            $(".kb").text(kbs)
            $(".sfzsb input:nth-child(1)").addClass("cm amb")
            $(".sfzsb input[type=button]").on('click', function () {
                $(this).addClass("cm").siblings().removeClass("cm");
                if ($(".cms").hasClass("cm")) {
                    var vreb = $(this).attr("id")
                    $(".kb").text(vreb)
                }
            })
            /*身份*/

            $.each(tema, function (key, val) {
                $(".sfzsa").append(
                    '<input type="button" class="cms" id="' + tema[key].id + '"  value="' + tema[key].identity_name + '" />'
                )
            })
            var kas = $(".sfzsa input:nth-child(1)").attr("id")
            $(".ka").text(kas)
            $(".sfzsa input:nth-child(1)").addClass("cm ama")
            $(".sfzsa input[type=button]").on('click', function () {
                $(this).addClass("cm").siblings().removeClass("cm");
                if ($(".cms").hasClass("cm")) {
                    var vrea = $(this).attr("id")
                    $(".ka").text(vrea)
                }
            })
        }
    })


    /*About请求*/

    $.ajax({
        type: "get",
        url: "http://47.100.16.157:86/api/home/index/get_addr",
        success: function (data) { //成功后回调
            var tem = data.data
            console.log(tem)
            $.each(tem, function (key, val) {
                $(".sea select").append(
                    '<option   id="' + tem[key].provinceid + '">' + tem[key].province + '</option>'
                )
            })
            $(".sea select").change(function () {

                var opt = $(this).find("option:checked").attr("id");
                $(".icd").text(opt)

                $(".seb select").find("option：:not(first-child)").remove();
                /*城市联动清除*/

                /*二级城市*/


                $.ajax({
                    type: "get",
                    url: "http://47.100.16.157:86/api/home/index/get_addr",
                    data: ({"province_id": opt}),
                    success: function (data) { //成功后回调
                        var tems = data.data
                        console.log(tems)
                        $.each(tems, function (key, val) {
                            $(".seb select").append(
                             '<option   id="' + tems[key].cityid + '">' + tems[key].city + '</option>'
                            )
                        })
                        $(".seb select").change(function () {
                            var opl = $(this).find("option:checked").attr("id");
                            $(".icds").text(opl)

                            console.log(opl)
                        })


                    }
                })


            })
        }
    })


})
 