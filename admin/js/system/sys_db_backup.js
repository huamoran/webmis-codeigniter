$(function(){
/*
列表
*/
	$('#listBG').webmis('TableOddColor');	//隔行换色
	$('#menus_table').webmis('TableAdjust');  //调整宽度
/*
导出
*/
	$('.action_exp').click(function(){
		var id = $('#listBG').webmis('GetInputID',{type:' '});
		if(id!=' '){
			$.webmis.win.open({title:'导出',width:480,height:340,overflow:true});
			$.post($base_url+'sys_db_backup/exp.html',{'table':id},function(data){
				$.webmis.win.load(data);   //加载内容
				expForm();  //表单验证
			});
		}else{
			$.webmis.win.open({content:'<b class="red">请选择！</b>',AutoClose:3});
		}
		return false;
	});
});

/*表单验证*/
function expForm(){
	$('#expSub').webmis('SubClass'); //按钮样式
	//验证提交
	$("#backForm").Validform({
		ajaxPost:true,
		tiptype:2,
		callback:function(data){
			$.Hidemsg();
			if(data.status=="y"){
				$.webmis.win.close();
				$.webmis.win.open({content:'<b class="green">操作成功</b>',target:'sys_db_restore.html',AutoClose:3});
			}else{
				$.webmis.win.close();
				$.webmis.win.open({content:'<b class="red">操作失败</b>',AutoClose:3});
			}
		}
	});
}