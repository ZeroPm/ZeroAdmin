layui.config({
	base : "js/"
}).use(['form','layer','jquery','table','laytpl','layedit'],function(){
	var form = layui.form,
		table = layui.table,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laytpl = layui.laytpl,
		layedit = layui.layedit,
		$ = layui.jquery;

	var province_id = $('#province_id').attr("value");
	var getTpl = uncoutTpl.innerHTML
		,view = document.getElementById('uncount');

	//公告表格渲染
	table.render({
	    elem: '#ancontent'
	    //数据接口
	    ,url: "<?= yii\helpers\Url::to(['getann']); ?>"
	    //参数
	    ,where: {province_id: province_id}
	    ////重新规定成功的状态码为 200，table 组件默认为 0
	    ,response: {
      	statusCode: 200 //重新规定成功的状态码为 200，table 组件默认为 0
    	}
    	,initSort: {field:'date', type:'desc'}
	    //开启工具
	    ,toolbar: '#toolbar'
	    ,parseData: function(res){
	    	console.log(res);
	    	if(res.code===200){
	    		laytpl(getTpl).render(res, function(html){
				  view.innerHTML = html;
				});			
	    	}
	    }
	    ,page: {
	    	layout: ['count', 'prev', 'page', 'next', 'skip', 'limit'] //自定义分页布局
	    	, limit: 5
	    	, groups: 5 //显示连续页码
	    	//, first: false //不显示首页
	      	//, last: false //不显示尾页
	      	, limits: [5,10]
	    }
	    ,cols: [[ //表头
	    	{type: 'checkbox', fixed: 'left',width:'4%'}
	      	,{field: 'title', title: '标题', width:'80%', sort: true, fixed: 'left',templet:'#titleTpl'}
	      	,{field: 'date', title: '发布时间', width:"15%", sort: true}
	    ]]
  	});

	  	//公告头工具栏事件
	  	table.on('toolbar(ancontent)', function(obj){
	  		var checkStatus = table.checkStatus(obj.config.id);
	  		switch(obj.event){
	  			case 'add-ann':
	  			var index = layui.layer.open({
	  				title : "添加公告",
	  				type : 2,
	                area: ['50%', '80%'], //宽高
	                content : "<?= yii\helpers\Url::to(['createann']);?>?"+"<?= $_SERVER['QUERY_STRING'];?>",
	                success : function(layero, index){
						// layer.close(index);
	     //            	//layer.msg('添加成功');
	     //            	table.reload('ancontent', {
	     //            		page: {
						// 	curr: 1 //重新从第 1 页开始
						// 	}
						// });
	                },
	                end: function () {
	                	table.reload('ancontent', {
	                		page: {
							curr: 1 //重新从第 1 页开始
							}
						});
	                }                
	            });	
			      	//layer.msg('添加');
			    break;
			    case 'processed':
			      	//layer.msg('批量处理');
			      	var data = checkStatus.data;
			      	var url = "<?= yii\helpers\Url::to(['processed']); ?>";
			      	//console.log(data);
			      	if(data.length!==0){
			      		layer.confirm('确定处理选中的信息？',{icon:3, title:'提示信息'},function(index){
			      			var index = layer.msg('处理中，请稍候',{icon: 16,time:false,shade:0.8});
			      			setTimeout(function(){
			      				$.post(url,{"keys":data},function(data){
			      					if(data.code===200){
			      						console.log(data);
			      						layer.msg(data.msg);
			      						layer.close(index);
			      						setTimeout(function(){
			      							table.reload('ancontent', {
			      								page: {
												curr: 1 //重新从第 1 页开始
											}
										});
			      						},500);
			      					}else{
			      						layer.close(index);
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
			      		});
			      	}else{
			      		layer.msg("请选择需要处理的公告",{icon: 5});
			      	}
			    break;
			    case 'delete-ann':
			      	//layer.msg('删除');
			      	var data = checkStatus.data;
			      	console.log(data);
			      	var url = "<?= yii\helpers\Url::to(['deleteann']); ?>";
			      	if(data.length!==0){
			      		layer.confirm('确定删除选中的信息？',{icon:3, title:'提示信息'},function(index){
			      			var index = layer.msg('删除中，请稍候',{icon: 16,time:false,shade:0.8});
			      			setTimeout(function(){
			      				$.post(url,{"keys":data},function(data){
			      					if(data.code===200){
			      						console.log(data);
			      						layer.msg(data.msg);
			      						layer.close(index);
			      						setTimeout(function(){
			      							table.reload('ancontent', {
			      								page: {
												curr: 1 //重新从第 1 页开始
											}
										});
			      						},500);
			      					}else{
			      						layer.close(index);
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
			      		});
			      	}else{
			      		layer.msg("请选择需要删除的公告",{icon: 5});
			      	}
			    break;
			    };
			  });

    //省份开关操作
    form.on('switch(province-switch)', function(obj){
        var href = this.checked?'active':'inactive';
            $.post(href+'?id='+this.name,function(data){
                //console.log(data);
                if(data.code===200){
                    layer.msg(data.msg);
                    setTimeout(function(){
                       //location.reload();
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
        return false;       
    }); 
  


    //添加主要内容
	$(window).one("resize",function(){
		$(".layui-content-add").click(function(){
			var index = layui.layer.open({
				title : "添加内容",
				type : 2,
                area: ['50%', '100%'], //宽高
				content : "<?= yii\helpers\Url::to(['content/create']);?>?"+"<?= $_SERVER['QUERY_STRING'];?>",
				success : function(layero, index){
					setTimeout(function(){
						layui.layer.tips('点击此处返回', '.layui-layer-setwin .layui-layer-close', {
							tips: 3
						});
					},500);
				},
                end: function () {
                	loadContent();
                }                
			});	
			//layui.layer.full(index);
		});
	}).resize();

	//渲染省份内容
	var contnetTpl = contentTpl.innerHTML
	,content_table = document.getElementById('content-table')
	,href = "<?= yii\helpers\Url::to(['content/item']); ?>";
	function loadContent(){
		$('.content-loading').removeClass('layui-hide');
		setTimeout(function(){

		$.get(href+'?province_id='+province_id,function(data){
			
	        console.log(data);
	        if(data.code===200){
	        	//layer.msg(data.msg);
	        	var contentdata = data.data;
	        	//json遍历实验
	   // 			Object.keys(contentdata).forEach(function(key){

				// console.log(key,contentdata[key]['title']);

				// 	Object.keys(contentdata[key]['inform']).forEach(function(key){

				// 		console.log(key,contentdata[key]['inform'][key]['title']);
				// 	});
				// });
				$('.content-loading').addClass('layui-hide');
	        	laytpl(contnetTpl).render(contentdata, function(html){
				  content_table.innerHTML = html;
				});
				form.render();
	        	//setTimeout(function(){
	              
	           //},500);
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
	loadContent();

    //编辑内容
	$("body").on("click",".layui-default-update",function(){  
        var href = $(this).attr("href");
        //console.log(href);
        var index = layui.layer.open({
            title : "修改内容",
            type : 2,
            area: ['50%', '100%'],
            content : href,
            success : function(layero, index){
            },
            end: function () {
                loadContent();
            }
        });	
        //layui.layer.full(index); //全屏当前弹出层
        return false;
	});

	//内容开关操作
    form.on('switch(content-switch)', function(obj){
    	console.log(this.value);
        var href = this.checked?"<?= yii\helpers\Url::to(['content/active']);?>":"<?= yii\helpers\Url::to(['content/inactive']);?>";
            $.post(href+'?id='+this.value,function(data){
                //console.log(data);
                if(data.code===200){
                    layer.msg(data.msg);
                    setTimeout(function(){
                       //location.reload();
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
        return false;       
    }); 

    //添加通知
    $("body").on("click",".add-info",function(){  
		var href = $(this).attr("href");
		//console.log(href);
        var index = layui.layer.open({
            title : "添加通知",
            type : 2,
            area: ['50%', '100%'],
            content : href,
            success : function(layero, index){
            },
            end: function () {
                loadContent();
            }
        });	
        //layui.layer.full(index); //全屏当前弹出层
        return false;
	});

	//编辑通知
	$("body").on("click",".layui-inform-update",function(){  
        var href = $(this).attr("href");
        //console.log(href);
        var index = layui.layer.open({
            title : "修改通知",
            type : 2,
            area: ['50%', '100%'],
            content : href,
            success : function(layero, index){
            },
            end: function () {
                loadContent();
            }
        });	
        //layui.layer.full(index); //全屏当前弹出层
        return false;
	});

	$("body").on("click",".layui-inform-delete",function(){
        var href = $(this).attr("href");
		layer.confirm('确定删除此通知吗？',{icon:3, title:'提示信息'},function(index){
            $.post(href,function(data){
                if(data.code===200){
                    layer.msg(data.msg);
                    layer.close(index);
                    setTimeout(function(){
                       loadContent();
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

});
