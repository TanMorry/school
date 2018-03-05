/**
 * Created by Administrator on 2017/9/22.
 */
/**
 * 移动端日历（浏览器打开请F12打开手机模拟器）
 *
 * 本日历可选择单个日期，也可选择两个或多个日期；默认可选单个日期；如需多选；可根据句中注释选择相应；
 * 日历纯手写；未加触摸事件；如需可自行添加，然后将 next() 和 prev() 方法对应相应事件；
 * @param   year,y,nian  年
 * @param   month,m,yue  月
 * @param   day          日
 * @param   td_time      当前时间戳
 * @param   week         本月1号周几
 * @param   days         本月天数
 * @param   dayw         上月天数
 * 好好好先生
 * 2017-9-22
 *//* DaTouWang URL: www.datouwang.com */
//日历开始

$(function(){
    var date=new Date();            //定义一个日期对象；
    var year=date.getFullYear();    //获取当前年份；
    var month=date.getMonth()+1;    //获取当前月份；
    var day=date.getDate();         //获取当前日期；
    //回填数据
    $('.year').text(year);
    $('.month').text(month);
    datt(year,month,'');
//下一月；
    $('.next').click(function(){
        next();
    });

//上一月；
    $('.prev').click(function(){
        prev();
    });

//返回本月；
    $('.tomon').click(function(){
        datt(year,month,'');
        $('.year').text(year);
        $('.month').text(month);
    });

});

function datt(nian,yue,ri){
  	 	
//    计算本月1号是周几；
    var week=new Date(nian+'-'+yue+'-1').getDay();

//计算本月有多少天；
    var days=new Date(nian,yue,0).getDate();
//计算上月有多少天；
    var dayw=new Date(nian,yue-1,0).getDate();

//将日历填回页面；拿出节假日
/*日历功能开始*/
//	 	$(".detail_footer").on('click',function(){
//	 		var hr = window.location.search.substr(1)
//	 		
//               		 $(".fade").show()
//	 		$("body").addClass("over")
//	 		$(this).hide()
//	 		$(".detail_footers").show()
//                  
//                })


  	 	
  	 
  	 
//     /*日历功能结束*/******
    var html='';
    for(var i=1;i<=days;i++){
        var time=new Date(nian,yue,i).getTime();
        if(yue+'-'+i =='1-1'){
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }else if(yue+'-'+i =='2-14'){
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }else if(yue+'-'+i =='3-8'){
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }else if(yue+'-'+i =='4-1'){
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }else if(yue+'-'+i =='5-1'){
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }else if(yue+'-'+i =='6-1'){
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }else if(yue+'-'+i =='7-1'){
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }else if(yue+'-'+i =='8-1'){
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }else if(yue+'-'+i =='9-10'){
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }else if(yue+'-'+i =='10-1'){
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }else if(yue+'-'+i =='11-11'){
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }else if(yue+'-'+i =='12-24'){
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }else if(yue+'-'+i =='12-25'){
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }else{
            html+="<li data-jr="+yue+"-"+ i +" data-id="+time+" data-date="+ nian+"-"+yue+"-"+i+"><span>"+i+"</span><label style='display:none'>"+ nian+"-"+yue+"-"+i+"|</label></li>"
        }
    }
    $('.date ul').html(html);
   var dbday = $('.date ul li label').text()
   var bdays = dbday.split('|')
   console.log(bdays)
   

//获取当前日期的时间戳；
    var ym=new Date().getFullYear();
    var mm=new Date().getMonth()+1;
    var dm=new Date().getDate();
  var td_time=new Date(ym,mm,dm).getTime();
  console.log(td_time)
  var hr = window.location.search.substr(1)
  $.ajax({
                    type: "get",
                     url: "http://tltltl.free.ngrok.cc//baolixiaoqu/public/api/home/ticket/date_time",
                    data:({"id":hr}),
                     headers:{
                 	"XX-Device-Type":"weixin",
                 	"XX-Token":"a93b8cf874fc9c064ad5112550291f5d957e5bbf5842859522362edb76051df7"
                  },
                    success: function(data) {
                    	console.log(data)
                    	var tes =data.data.date
                    	var tess =data.data.date.length
                    	console.log(tess)
                    	$(".over_db").text(tess)
                    	if(data.code==1){
                    		$.each(tes, function (key, val) {
                    		var stringTime = tes[key];
var timestamp2 = Date.parse(new Date(stringTime));
timestamp2 = timestamp2 / 1000;
//2014-07-10 10:21:12的时间戳为：1404958872 
var stb =timestamp2+ "000";
console.log(stb)
                    			
                    			$(".over_date").append(
                    				'<a>'+tes[key]+'|</a>'+
                    				'<i>'+stb+'|</i>'
                    				
                    				
                    			)
                    			
                    			})
                    	}
                    }
                    
                  })
  
 
   setTimeout(function(){
   	 var stringTime = "2018-1-26";
   var sta = $('.over_date a').text()
   var stas = sta.split('|')
                    		    console.log(stas)
                    		      console.log(stringTime)
var timestamp2 = Date.parse(new Date(stringTime));
timestamp2 = timestamp2 / 1000;
//2014-07-10 10:21:12的时间戳为：1404958872 
console.log( timestamp2+ "000");
                    		  },3000);
 


console.log(days)
 var dat = $(".over_db").text()

// 日历里面时间戳跟当前时间戳比较；大于等于 可点击；小于不可点击；日期默认单选
    for(var k=0;k<dat;k++){
    	
        var tt_time=$('.date ul li').eq(k).attr('data-id');
    
//      var num=0;
        //判断是否是周六或周日；添加特殊样式
//    var wk=new Date($('.date ul li').eq(k).attr('data-date')).getDay();
//    if(wk==6||wk==0){
//
//  $('.date ul li').eq(k).addClass('gren')
//    }
        if(tt_time > td_time){
        	console.log(tt_time)
        	console.log(td_time)
            $('.date ul li').eq(k).click(function(){
                var _this=$(this);
                //如果是双选日期则使用这个；
                    /* if(num%2==0){
                    _this.addClass('act_date');     //选择开始日期
                    _this.siblings('li').removeClass('act_date');
                    var dr=_this.attr('data-date');
                    console.log(dr);
                    num++;
                }else if(num%2!=0){
                    _this.addClass('act_ds');       //选择结束日期
                    _this.siblings('li').removeClass('act_ds');
                    var dd=_this.attr('data-date');
                    console.log(dd);
                    num++;
                }*/
                //如果是单选日期则使用这个；
                _this.addClass('act_date');     //选择开始日期
                _this.siblings('li').removeClass('act_date');
                var dr=_this.attr('data-date');
                console.log(dr);
                $(".rq_day").text(dr)
            });
        }else if(tt_time < td_time){
          $('.date ul li').eq(k).addClass('act_date');
          $('.date ul li').eq(k).click(function(){
              var _this=$(this);
             _this.addClass('act_date');
             _this.siblings('li').removeClass('act_date');
              var dr=_this.attr('data-date');
              // console.log(dr);
          });
        }else{
            $('.date ul li').eq(k).addClass('no_date');
        }
    }

//计算前面空格键；
    var html2='';
    for(var j=dayw-week+1;j<=dayw;j++){
//      html2+="<li class='no_date'>"+j+"</li>";
    }
    $('.date ul li').eq(0).before(html2);

//计算后面空格键；
    var html3='';
    for(var x=1;x<43-days-week;x++){
//      html3+="<li class='no_date'>"+x+"</li>";
    }
    $('.date ul li').eq(days+week-1).after(html3);
}

//找出节假日；


//下一月；
function next(){
    var y=$('.year').text();
    var m=$('.month').text();
    if(m==12){
        y++;
        m=1;
    }else{
        m++;
    }
    $('.year').text(y);
    $('.month').text(m);
    datt(y,m,'')
}
//上一月；
function prev(){
    var y=$('.year').text();
    var m=$('.month').text();
    if(m==1){
        y--;
        m=12;
    }else{
        m--;
    }
    $('.year').text(y);
    $('.month').text(m);
    datt(y,m,'')
}