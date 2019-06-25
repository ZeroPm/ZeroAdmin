layui.config({
	base : "js/"
}).use(['form','layer','jquery','laydate'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		$ = layui.jquery;

	$("body").on("click",".wechat-shorturl-button",function(){
        console.log($('.link-input').val());
        var url = $('.link-input').val();
        if(url){
        var href="<?= yii\helpers\Url::to(['brand/shorturl'])?>";
        $.post(href,{'shorturl':url},function(data,index){
        console.log(data);
            if(data.code===200){
                layer.msg(data.msg);
                $('.shorturl-input').val(data.data);
            }else{
                layer.msg(data.msg);
            }
        },"json").fail(function(a,b,c){
            if(a.status==403){
                layer.msg('没有权限');
            }else{
                layer.msg('系统错误');
            }
        });
    }else{
    	layer.msg('请填写需要转换的链接地址', {icon: 5}); 
    }
    }); 
});
