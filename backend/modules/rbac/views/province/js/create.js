layui.config({
	base : "js/"
}).use(['form','layer','jquery','laydate'],function(){
	var laydate = layui.laydate;
	  	//添加公告时间选择器渲染
    laydate.render({
    	elem: '#add-date' //指定元素
  	});
});
