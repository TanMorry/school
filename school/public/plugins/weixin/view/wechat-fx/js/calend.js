$('#ca').calendar({
        width: 320,
        height: 320,
        data: [
			{
			  date: '2018/1/25',
			  value: 'Christmas Eve'
			},
			{
			  date: '2015/12/25',
			  value: 'Merry Christmas'
			},
			{
			  date: '2016/01/01',
			  value: 'Happy New Year'
			}
		],

        onSelected: function (view, date, data) {
          var checkDate = date.format("yyyy-mm-dd"); //选中的日期
        console.log('view:' + view) 
        console.log('data:' + (data || 'None'));
        console.log(checkDate);

        
        }
    });
