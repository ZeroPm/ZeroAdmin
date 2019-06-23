layui.config({
	base : "js/"
}).use(['form','layer','jquery','laydate','laytpl'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laytpl = layui.laytpl,
		$ = layui.jquery;

	var subTpl = suboperationTpl.innerHTML
	,suboperationdata = document.getElementById('sub-operation-data')
	,firstTpl = firstoperationTpl.innerHTML
	,firstoperationdata = document.getElementById('first-operation-data')
	,href = "<?= yii\helpers\Url::to(['cuser/operation']);?>";
	function loadOperation(){
		$('.content-loading').removeClass('layui-hide');
		setTimeout(function(){
			//console.log(uuid);
		$.get(href+'?'+"<?= $_SERVER['QUERY_STRING'];?>",function(data){
	        console.log(data);
	        if(data.code===200){
	        	//layer.msg(data.msg);
	        	var onedata = data.onedata;
	        	var twodata = data.twodata;
	        	//json遍历实验
	   // 			Object.keys(data.item).forEach(function(key){

				// console.log(key,data.item[key]['id']);

				// 	// Object.keys(contentdata[key]['inform']).forEach(function(key){

				// 	// 	console.log(key,contentdata[key]['inform'][key]['title']);
				// 	// });
				// });
				$('.content-loading').addClass('layui-hide');
	        	laytpl(subTpl).render(twodata, function(html){
				  suboperationdata.innerHTML = html;
				});

				laytpl(firstTpl).render(onedata, function(html){
				  firstoperationdata.innerHTML = html;
				});

	        	setTimeout(function(){
	              
	           },500);
	        }else{
	        	layer.msg(data.msg);
	        }
	    },"json").fail(function(a,b,c){
	    	if(a.status==403){
	    		layer.msg('没有权限',{icon: 5});
	    	}else{
	    		layer.msg('系统错误',{icon: 5});
	    	}
	    });
		},800);
	}
	loadOperation();

	var code = document.getElementById('code-str');
	//公众号扫码关注tip展示
	$("body").on("mouseenter",".scene-qr-code",function(e){
		var that = this;
		// console.log(.parent());
		var code_str = code.getAttribute("data")?code.getAttribute("data"):'原始公众号二维码（小程序中嵌入的）';
		//小tips
		layer.tips('二维码扫码场景描述：'+code_str,that, {
		  tips: [1, '#009688'],
		  time: 0,
		  area: ['400px',]
		});
	});

	//公告tip隐藏
	$("body").on("mouseleave",".scene-qr-code",function(){
		layer.closeAll('tips');
	});


});
