

//创建 XMLHttpRequest
//对象不同的浏览器使用不同的方法来创建 XMLHttpRequest 对象。
//Internet Explorer 使用 ActiveXObject。
//其他浏览器使用名为 XMLHttpRequest 的 JavaScript 内建对象。
function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 // Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}
//**************************************
//function check()   异步调用函数
//pageName           加载的页面
//dataName           传送参数名
//getValueID         传送参数值的ID
//setValueID         返回值的显示的ID
function addshow(pageName,dataName,getValueId,setValueId){  
	xmlhttp=GetXmlHttpObject();
	
	url= ""+pageName+"?"+dataName+"="+encodeURI(document.getElementById(getValueId).value);
	//url=encodeURI(url);
	//alert(url);
	xmlhttp.open("GET",url,true); //可以理改GET为POST
	xmlhttp.send(null); //传输大一点的数据通过POST方式传送
	xmlhttp.onreadystatechange=function(){ 
		if (xmlhttp.readyState == 4){ 
			if (200==xmlhttp.status){ //浏览器返回状态
				document.getElementById(setValueId).innerHTML=xmlhttp.responseText; 
			}else{ 
				document.getElementById(setValueId).innerHTML="数据加载中...";  
			} 
		} 
	} 
} 




function getIP(showID){
	xmlhttp=GetXmlHttpObject();
	//url= ""+pageName+"?"+dataName+"="+escape(getValueId);
	var   groupname = document.getElementById('GroupName').value ;
	url="ajax/getip.php?groupname="+encodeURI(groupname);	
	//alert(url);
	xmlhttp.open("GET",url,true); //可以理改GET为POST
	xmlhttp.send(null); //传输大一点的数据通过POST方式传送
	xmlhttp.onreadystatechange=function(){ 
		if (xmlhttp.readyState == 4){ 
			if (200==xmlhttp.status){ //浏览器返回状态
				document.getElementById(showID).value=xmlhttp.responseText; 
			}
		} 
	} 
}

function findPassword(getID,showID){
	xmlhttp=GetXmlHttpObject();
	//url= ""+pageName+"?"+dataName+"="+escape(getValueId);
	var   username = document.getElementById(getID).value ;
	url="ajax/findpasswd.php?UserName=" + username;	
	xmlhttp.open("GET",url,true); //可以理改GET为POST
	xmlhttp.send(null); //传输大一点的数据通过POST方式传送
	xmlhttp.onreadystatechange=function(){ 
		if (xmlhttp.readyState == 4){ 
			if (200==xmlhttp.status){ //浏览器返回状态
				document.getElementById(showID).value=xmlhttp.responseText; 
			}
		} 
	} 
}



function getuserinfo(){
	var UserName=document.getElementById("Add_UserName").value;
	var User_Password=document.getElementById("User_Password").value;
	var Name=document.getElementById("Add_Name").value;
	var GroupName=document.getElementById("GroupNameSelect").value;
	var IDNumber=document.getElementById("IDNumber").value;
	var Address=document.getElementById("Address").value;
	var Mail=document.getElementById("Mail").value;
	var HomePhone=document.getElementById("HomePhone").value;
	var Mobile=document.getElementById("Mobile").value;
	var Manager=document.getElementById("Manager").value;
	var Installer=document.getElementById("Installer").value;
	var StartDate=document.getElementById("StartDate").value;
	var products_ID=document.getElementById("products_ID").value;
	var Framed_IP_Address=document.getElementById("Framed_IP_Address").value;
	if(UserName==""){
		alert("用户不能为空!");
		return false;
	}
	if(/^[a-zA-Z0-9_-]*$/.test(UserName)){
	}else{
		alert("必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成");
		return false;		
	}
	if(User_Password==""){
		alert("密码不能为空!");
		return false;		
	}
	if(/^[a-zA-Z0-9]*$/.test(User_Password)){
	}else{
		alert("必须输入一个有效的密码! 密码由字母a-z, A-Z 或者数字0-9组成");
		return false;			
	}
	if(GroupName==""){
		alert("项目名不能为空");
		return false;
	}
	
	if(Name==""){
		alert("实名不能为空!");
		return false;		
	}	
	if(IDNumber==""){
		alert("身份证为空! 必须输入一个有效的身份证号码!");
		return false;		
	}	
	if(Address==""){
		alert("装机地址为空! 必须输入一个有效的装机地址!");
		return false;		
	}	
	if(Mobile==""){
		alert("客户移动电话号码为空! 必须输入一个有效的移动电话号码.!");
		return false;		
	}	
	if(Manager==""){
		alert("客户经理姓名为空! 必须输入一个客户经理姓名!");
		return false;		
	}	
	if(Installer==""){
		alert("装机员姓名为空! 必须输入一个装机员姓名!");
		return false;		
	}	
	if(StartDate==""){
		alert("开通日期为空! 必须输入一个开通日期.!");
		return false;		
	}	
//str="UserName="+UserName+"&User_PasswordNameSpan="+User_PasswordNameSpan+"Name="+Name+"GroupName="+GroupName+"IDNumber="+IDNumber+"Address="+Address+"Mail="+Mail+"HomePhone="+HomePhone+"Mobile="+Mobile+"Manager="+Manager+"Installer="+Installer+"StartDate="+StartDate+"products_ID="+products_ID+"Framed_IP_Address="+Framed_IP_Address+"Framed_MTU="+Framed_MTU;
	
	
}
