layui.config({
	base : "js/"
}).use(['form','layer','jquery','laydate'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		$ = layui.jquery,
        laydate = layui.laydate;

    laydate.render({
        elem: '#star-date' //指定元素
        ,type: 'datetime'
        ,format: 'yyyy-MM-dd HH' //可任意组合
    });
	//表格操作 [添加、查看、修改、删除、批量删除、全选]

	//1、全选功能
	form.on('checkbox(allChoose)', function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		child.each(function(index, item){
			item.checked = data.elem.checked;
		});
		form.render('checkbox');
	});

	//2、通过判断文章是否全部选中来确定表头全选按钮是否选中
	form.on("checkbox(choose)",function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		var childChecked = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"]):checked')
		if(childChecked.length === child.length){
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = true;
		}else{
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = false;
		}
		form.render('checkbox');
	});
    
	//3、添加
	$(window).one("resize",function(){
		$(".layui-default-add").click(function(){
			var index = layui.layer.open({
				title : "添加文章",
				type : 2,
                area: ['100%', '100%'], //宽高
				content : "<?= yii\helpers\Url::to(['create']); ?>",
				success : function(layero, index){
					setTimeout(function(){
						layui.layer.tips('点击此处返回', '.layui-layer-setwin .layui-layer-close', {
							tips: 3
						});
					},500);
				},
                end: function () {
                    location.reload();
                }                
			});	
			layui.layer.full(index);
		});
	}).resize();
    
    //4、查看
	$("body").on("click",".layui-default-view",function(){
        var href = $(this).attr("href");
        console.log(href);
        var index = layui.layer.open({
            title : "查看文章",
            type : 2,
            area: ['100%', '100%'], //宽高
            content : href,
            success : function(layero, index){
                setTimeout(function(){
                    layui.layer.tips('点击此处返回', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });
                },500);
            }
        });	
        layui.layer.full(index);//全屏当前弹出层
        return false;
	});
    
    //5、修改
	$("body").on("click",".layui-default-update",function(){  
        var href = $(this).attr("href");
        console.log(href);
        var index = layui.layer.open({
            title : "修改文章",
            type : 2,
            area: ['100%', '100%'],
            content : href,
            success : function(layero, index){
                setTimeout(function(){
                    layui.layer.tips('点击此处返回', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });
                },500);
            },
            end: function () {
                location.reload();
            }
        });	
        layui.layer.full(index); //全屏当前弹出层
        return false;
	});

    //6、删除
	$("body").on("click",".layui-default-delete",function(){
        var href = $(this).attr("href");
		layer.confirm('确定删除此文章吗？',{icon:3, title:'提示信息'},function(index){
            $.post(href,function(data){
                if(data.code===200){
                    layer.msg(data.msg);
                    layer.close(index);
                    setTimeout(function(){
                       location.reload();
                    },500);
                }else{
                    layer.close(index);
                    layer.msg(data.msg);
                }
            },"json").fail(function(a,b,c){
                if(a.status==403){
                    layer.msg('没有权限');
                }else{
                    layer.msg('系统错误');
                }
            });
		});
        return false;
	});

    //Keyword通知项灵活变更
    $("body").on("click",".add-keyword",function(){
        var href = $(this).attr("href");
        console.log($(".keyword1").children("label").text());
        //$(".keyword1").append($(".keyword1").clone());
        // layer.confirm('确定删除此文章吗？',{icon:3, title:'提示信息'},function(index){
        //     $.post(href,function(data){
        //         if(data.code===200){
        //             layer.msg(data.msg);
        //             layer.close(index);
        //             setTimeout(function(){
        //                location.reload();
        //             },500);
        //         }else{
        //             layer.close(index);
        //             layer.msg(data.msg);
        //         }
        //     },"json").fail(function(a,b,c){
        //         if(a.status==403){
        //             layer.msg('没有权限');
        //         }else{
        //             layer.msg('系统错误');
        //         }
        //     });
        // });
        // return false;
    });  

    //异步添加通知和编辑通知
    form.on('submit(go)', function(data){
        var formdata = data.field
            ,url = formdata['id'] ? "<?= yii\helpers\Url::to(['updates']);?>" : "<?= yii\helpers\Url::to(['createadd']);?>";
            console.log(formdata);
          //var index = layer.msg('处理中，请稍候',{icon: 16,time:false,shade:0.8});
          //setTimeout(function(){
            $.post(url,{"keys":formdata},function(data){
                if(data.code===200){
                    console.log(data);
                    layer.msg(data.msg);
                    layer.closeAll('iframe');
                    setTimeout(function(){

                    },500);
                }else{

                }
            },"json").fail(function(a,b,c){
                if(a.status==403){
                    layer.msg('没有权限',{icon: 5});
                }else{
                    layer.msg('系统错误',{icon: 5});
                }
            });
        //},800);      
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
      });  
});
