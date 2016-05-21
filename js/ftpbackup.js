function checkForm(){
	
	var interval = $("#interval").val().trim();
	if(interval == ""){
		$("#interval").focus();
		$("#intervalMsg").html("<font style='color:red'>备份间隔周期不能为空!</span>");
		return false;
	}
	
	var reg = /^[1-9]\d*$/;
	if(!reg.test(interval)){
		$("#interval").focus();
		$("#intervalMsg").html("<font style='color:red'>备份间隔周期必须是大于0的数字!</span>");
		return false;
	}else{
		$("#intervalMsg").html("");
	}
	
	var savedDate = $("#savedDate").val().trim();
	if(savedDate == ""){
		$("#savedDate").focus();
		$("#savedDateMsg").html("<font style='color:red'>本地保存期不能为空!</span>");
		return false;
	}
	
	if(!reg.test(savedDate)){
		$("#savedDate").focus();
		$("#savedDateMsg").html("<font style='color:red'>本地保存期必须是大于0的数字!</span>");
		return false;
	}else{
		$("#savedDateMsg").html("");
	}
	
	var ftpPort = $("#ftpPort").val().trim();
	if(ftpPort == ""){
		$("#ftpPort").focus();
		$("#ftpPortMsg").html("<font style='color:red'>ftp端口不能为空!</span>");
		return false;
	}
	
	if(!reg.test(ftpPort)){
		$("#ftpPort").focus();
		$("#ftpPortMsg").html("<font style='color:red'>ftp端口只能是数字!</span>");
		return false;
	}
	
	var port = parseInt(ftpPort);
	if(port < 0 || port > 65535){
		$("#ftpPort").focus();
		$("#ftpPortMsg").html("<font style='color:red'>ftp端口超出指定范围!</span>");
		return false;
	}else{
		$("#ftpPortMsg").html("");
	}
	
	if($("#ftpHost").val().trim()==""){
		$("#ftpHost").focus();
		$("#ftpHostMsg").html("<font style='color:red'>ftp主机不能为空!</span>");
		return false;
	}else{
		$("#ftpHostMsg").html("");
	}

	if($("#ftpUser").val().trim()==""){
		$("#ftpUser").focus();
		$("#ftpUserMsg").html("<font style='color:red'>ftp用户名不能为空!</span>");
		return false;
	}else{
		$("#ftpUserMsg").html("");
	}
	
	if($("#ftpPwd").val().trim()==""){
		$("#ftpPwd").focus();
		$("#ftpPwdMsg").html("<font style='color:red'>ftp密码不能为空!</span>");
		return false;
	}else{
		$("#ftpPwdMsg").html("");
	}
	return true;
}

$(document).ready(function(){
	$("input[name=status]").click(function(){
		if($(this).val()=="enable"){
			$("#act").val('备份');
		}else{
			$("#act").val('禁用');
		}
	});
});