layui.config({
	base : "js/"
}).use(['form','layer','jquery','table','laytpl','layedit','element'],function(){
	var form = layui.form,
		table = layui.table,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laytpl = layui.laytpl,
		layedit = layui.layedit,
		$ = layui.jquery;
		// element = layui.element;

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
	      	,{field: 'title', title: '标题', width:'40%', sort: true, fixed: 'left',templet:'#titleTpl'}
	      	,{field: 'remark', title: '备注', width:'40%', sort: true, fixed: 'left'}
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
						//curr: 1 //重新从第 1 页开始
						}
					});
                }                
            });	
		      	//layer.msg('添加');
		    break;
		    case 'processed':
		      	//layer.msg('批量处理');
		      	var data = checkStatus.data;
		      	// console.log(data);
		      	var url = "<?= yii\helpers\Url::to(['processed']); ?>";
		      	//console.log(data);
		      	if(data.length!==0){
		      	// 	layer.confirm('确定处理选中的信息？',{icon:3, title:'提示信息'},function(index){
		      	// 		var index = layer.msg('处理中，请稍候',{icon: 16,time:false,shade:0.8});
		      	// 		setTimeout(function(){
		      	// 			$.post(url,{"keys":data,"remark":},function(data){
		      	// 				if(data.code===200){
		      	// 					console.log(data);
		      	// 					layer.msg(data.msg);
		      	// 					layer.close(index);
		      	// 					setTimeout(function(){
		      	// 						table.reload('ancontent', {
		      	// 							page: {
									// 		curr: 1 //重新从第 1 页开始
									// 	}
									// });
		      	// 					},500);
		      	// 				}else{
		      	// 					layer.close(index);
		      	// 					layer.msg(data.msg);
		      	// 				}
		      	// 			},"json").fail(function(a,b,c){
		      	// 				if(a.status==403){
		      	// 					layer.msg('没有权限',{icon: 5});
		      	// 				}else{
		      	// 					layer.msg('系统错误',{icon: 5});
		      	// 				}
		      	// 			});
		      	// 		},800);
		      	// 	});
		      	    var index = layui.layer.open({
			            title : "公告处理备注",
			            type : 1,
			            area: ['50%', '60%'],
			            content : $(".remark-form").removeClass("layui-hide"),
			            success : function(fromData, callback){
			            	form.on('submit(remark-go)', function(fromData,callback){
			            		console.log(fromData);
			            		var remark = '';
			            		if(fromData.field.remark==5){
			            			remark = fromData.field.desc;
			            		}else{
			            			remark = fromData.field.remark;
			            		}
			            		if(remark){
									$.post(url,{"keys":data,"remark":remark},function(data,callback){
							                if(data.code===200){
							                	layer.close(index);
							                    layer.msg(data.msg);
							                    setTimeout(function(){
				      							table.reload('ancontent', {
				      								page: {
													//curr: 1 //重新从第 1 页开始
												}
											});
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
						  		}else{
						  			layer.msg("请填写处理备注",{icon: 5});
						  		}
							  // console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
							  // console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
							  // console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}
							  return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
							});
			            },
			            end: function () {
			                $(".remark-form").addClass("layui-hide");
			            }
			        });	
			        //layui.layer.full(index); //全屏当前弹出层
			        return false;
				// form.on('select(get-remark)', function(data){
				//   console.log(data);
				// });
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
											//curr: 1 //重新从第 1 页开始
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
	//监听公告备注select
	form.on('select(get-remark)', function(data){
	  console.log(data);
	  if(data.value==5){
	  	$("#desc").removeAttr('style');
	  }else{
	  	$("#desc").css("display","none");
	  }
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

	//删除通知
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

	//编辑省份链接
	$("body").on("click",".updata-link",function(){  
		//console.log(123);
        var index = layui.layer.open({
            title : "编辑链接",
            type : 1,
            area: ['50%', '40%'],
            content : $("#link-form").removeClass("layui-hide"),
            success : function(layero, index){
            	form.on('submit(go)', function(data,index){
					var href="<?= yii\helpers\Url::to(['province/editlk'])?>";
					$.post(href,data.field,function(data,index){
			                if(data.code===200){
			                    layer.msg(data.msg);
			                    setTimeout(function(){
			                    	layer.close(index);
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
				  // console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
				  // console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
				  // console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}
				  return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
				});
            },
            end: function () {
                $("#link-form").addClass("layui-hide");
            }
        });	
        //layui.layer.full(index); //全屏当前弹出层
        return false;
	});

	//渲染富文本
    var contents = layedit.build('remark-edit');

	//编辑省份链接
	$("body").on("click",".updata-remark",function(){  
		//console.log(123);
        var index = layui.layer.open({
            title : "编辑笔记",
            type : 1,
            area: ['60%', '65%'],
            content : $(".province-remark-form").removeClass("layui-hide"),
            success : function(layero, index){
            	form.on('submit(province-remark-go)', function(data,index){
					var href="<?= yii\helpers\Url::to(['province/editremark'])?>";
					data.field.remark = layedit.getContent(contents);
					$.post(href,data.field,function(data,index){
			                if(data.code===200){
			                    layer.msg(data.msg);
			                    setTimeout(function(){
			                    	layer.close(index);
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
				  // console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
				  // console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
				  // console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}
				  return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
				});
            },
            end: function () {
                $(".province-remark-form").addClass("layui-hide");
            }
        });	
        //layui.layer.full(index); //全屏当前弹出层
        return false;
	});

	//基础资料tip展示
	$("body").on("mouseenter",".icon-about-title",function(e){
		var that = this;
		//小tips
		layer.tips('1.本栏目最后的数据统计有缓存，缓存时间在5分钟。',that, {
		  tips: [1, '#009688'],
		  time: 0,
		  area: ['400px',]
		});
	});

	//公告tip展示
	$("body").on("mouseenter",".icon-about-ann",function(e){
		var that = this;
		//小tips
		layer.tips('1.公告添加后，状态为未处理，此时公告不会在小程序上展示。</br>2.使用批量处理后的公告才会在小程序上展示。</br>3.删除公告后，你和小程序都不展示此公告。</br>4.日常公告处理要求。在出现新的未处理公告后，请点击公告链接进入对应内容页，再根据公告整理手册进行内容整理，整理完成后方可对公告进行批量处理，让小程序用户查看。</br>5.处理公告时，请选择备注或者填写备注。以便追溯。',that, {
		  tips: [1, '#009688'],
		  time: 0,
		  area: ['400px',]
		});
	});

	//公告tip隐藏
	$("body").on("mouseleave",".icon-about-ann,.icon-about-con,.icon-about-title,.layui-icon-log",function(){
		layer.closeAll('tips');
	});

	//内容tip展示
	$("body").on("mouseenter",".icon-about-con",function(){
		var that = this;
		layer.tips('1.内容开启且省份亦开启的条件下，小程序用户才能看到且每个内容下挂载的通知才能有效的通知用户。</br>2.当内容只有标题和链接时，小程序用户看到的样式如下：<img src="http://47.99.136.102/qrcode/content_example.png"></br>3.通知创建好后，请进行通知测试，以免出现通知内容错误或通知功能BUG。如需自己能接收通知测试内容请联系管理员。</br>4.内容的整理请根据公告处理手册执行。</br>5.定时任务每天只会对当天需要通知的消息进行通知，且最晚通知时间不能超过晚上10点。</br>6.定时任务每天10:05启动，每2小时执行一次，每天最晚的一次是22:05分。</br>7.通知处理成功的边框为绿色，正在通知的为橙色，失败或者异常的为红色（通知时间后2小时还未完成通知的），后续等待通知为灰色。</br>8.当天需要通知的信息，在通知时间栏会以橙色标记。', that, {
		  tips: [1, '#009688'],
		  time: 0,
		  area: ['400px',]
		});
	});

	//已发送通知数据展示
	$("body").on("mouseenter",".layui-icon-log",function(){
		var that = this;
		//console.log(123);
		layer.tips('通知用户数：'+$(that).attr("data"), that, {
		  tips: [1, '#009688'],
		  time: 0,
		  area: ['200px',]
		});
	});

	//消息通知测试
	$("body").on("click",".layui-inform-send",function(){  
        var href = $(this).attr("href");
        var data = $(this).attr("data");
        var that = $(this);
        that.addClass('layui-disabled');
        that.attr("disabled","disabled");
        that.text("发送中...")
        //console.log();
        setTimeout(function(){
	        $.post(href,{data:data},function(data){     	
        		console.log(data);
                if(data.code===200){
                    layer.msg(data.msg);
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
            that.removeClass('layui-disabled');
	        that.removeAttr("disabled");
	       	that.text("通知测试")
        },3000);
        return false;
	});

	// //提交编辑的链接
	// function updataLink(){
	// 	form.on('submit(go)', function(data){
	// 	var href="<?= yii\helpers\Url::to(['province/editlk'])?>";
	// 	$.post(href,data.field,function(data){
 //                if(data.code===200){
 //                    layer.msg(data.msg);
 //                    setTimeout(function(){
 //                    	layer.close(index);
 //                       	location.reload();
 //                    },500);
 //                }else{
 //                    layer.close(index);
 //                    layer.msg(data.msg);
 //                }
 //            },"json").fail(function(a,b,c){
 //                if(a.status==403){
 //                    layer.msg('没有权限');
 //                }else{
 //                    layer.msg('系统错误');
 //                }
 //            });
	//   // console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
	//   // console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
	//   // console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}
	//   return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
	// });
	// }
	

});
