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
function ajaxInput(pageName,dataName,getValueId,setValueId){  
	xmlhttp=GetXmlHttpObject();
	url= ""+pageName+"?"+dataName+"="+escape(document.getElementById(getValueId).value);
	xmlhttp.open("GET",url,true); 
	xmlhttp.send(null); 
	xmlhttp.onreadystatechange=function(){ 
		if (xmlhttp.readyState == 4){ 
			if (200==xmlhttp.status){ //浏览器返回状态
				document.getElementById(setValueId).innerHTML=xmlhttp.responseText; 
			}else{ 
				document.getElementById(setValueId).innerHTML=aj_dataLoad;//数据加载中... 
			} 
		} 
	} 
} 
  
//**************************************
//function check()   异步调用函数
//pageName           加载的页面
//dataName           传送参数名
//getValueID         传送参数值的ID
//setValueID         返回值的显示的ID
function ajaxInputMore(pageName,dataName,getValueId,dataName2,getValueId2,dataName3,getValueId3,dataName4,getValueId4,setValueId){  
	xmlhttp=GetXmlHttpObject();
	url= ""+pageName+"?"+dataName+"="+escape(document.getElementById(getValueId).value)+"&"+dataName2+"="+escape(document.getElementById(getValueId2).checked)+"&"+dataName3+"="+escape(document.getElementById(getValueId3).value)+"&"+dataName4+"="+escape(document.getElementById(getValueId4).value); 
	xmlhttp.open("GET",url,true); 
	xmlhttp.send(null); 
	xmlhttp.onreadystatechange=function(){ 
		if (xmlhttp.readyState == 4){ 
			if (200==xmlhttp.status){ //浏览器返回状态
				document.getElementById(setValueId).innerHTML=xmlhttp.responseText; 
			}else{ 
				document.getElementById(setValueId).innerHTML=aj_dataLoad;//数据加载中... 
			} 
		} 
	} 
} 



//**************************************
//function check()   异步调用函数
//pageName           加载的页面
//dataName           传送参数名
//getValueID         传送参数值的ID
//setValueID         返回值的显示的ID
function P_ajaxInput(pageName,dataName,getValueId,setValueId){  
	xmlhttp=GetXmlHttpObject();
	url= ""+pageName+"?"+dataName+"="+escape(document.getElementById(getValueId).value);
	xmlhttp.open("GET",url,true); 
	xmlhttp.send(null); 
	xmlhttp.onreadystatechange=function(){ 
		if (xmlhttp.readyState == 4){ 
			if (200==xmlhttp.status){ //浏览器返回状态
				document.getElementById(setValueId).value=xmlhttp.responseText; 
			}else{ 
				document.getElementById(setValueId).innerHTML=aj_dataLoad; //数据加载中...
			} 
		} 
	} 
}


//**************************************
//function ajaxGetIp()   异步调用函数
//pageName               加载的页面
//dataName               传送参数名
//getValueID             传送参数值的ID
function ajaxGetIp(pageName,dataName,getValueId,setValueId){  
	xmlhttp=GetXmlHttpObject();
	url= ""+pageName+"?"+dataName+"="+escape(document.getElementById(getValueId).value);
	xmlhttp.open("GET",url,true); 
	xmlhttp.send(null); 
	xmlhttp.onreadystatechange=function(){ 
		if (xmlhttp.readyState == 4){ 
			if (200==xmlhttp.status){ //浏览器返回状态
				document.getElementById(setValueId).value=xmlhttp.responseText; 
			}else{ 
				document.getElementById(setValueId).value=aj_dataLoad;//数据加载中... 
			} 
		} 
	} 
} 




//**********************	
//这是input表单样式调用函数
//
function input_tip_show(msg,id){
	document.getElementById("span_"+id).innerHTML="<font color='#666666'>"+msg+"</font>";
}

function input_style_on(id){
	document.getElementById(id).className='input_on';
}
function input_style_out(id){
	document.getElementById(id).className='input_out';
}




//********************************************************************
//常用的表单判断
function getDate() 
{ 
  var d,s,t; 
  d=new Date(); 
  s=d.getFullYear().toString(10)+"-"; 
  t=d.getMonth()+1; 
  s+=(t>9?"":"0")+t+"-"; 
  t=d.getDate(); 
  s+=(t>9?"":"0")+t+" "; 
  t=d.getHours(); 
  s+=(t>9?"":"0")+t+":"; 
  t=d.getMinutes(); 
  s+=(t>9?"":"0")+t+":"; 
  t=d.getSeconds(); 
  s+=(t>9?"":"0")+t; 
  return s; 
}

function isIP(value){//判断IP地址
  var pattern = /^\d{1,3}(\.\d{1,3}){3}$/;
  if (!pattern.exec(value)) {
    alert(aj_IP);//请输入一个有效的IP地址
    return false;
  }
  var ary = value.split('.');
  for(key in ary)
  {
    if (parseInt(ary[key]) > 255){
      alert(aj_IP);//请输入一个有效的IP地址
      return false;
    }
  }
  return true ;
}
function checkIP(ip)  {
 var ipArray,j; 
 if(/[A-Za-z_-]/.test(ip)){  
  if(!/^([a-z0-9]+.*)+((com)|(net)|(org)|(gov.cn)|(info)|(cc)|(com.cn)|(net.cn)|(org.cn)|(com.ru)|(net.ru)|(org.ru)|(name)|(biz)|(hk)|(tv)|(cn))$/.test(ip)){
   alert(urlvaltrue); //"不是正确的域名"
   return false;
  }else return  true;
 }
 else{
  ipArray = ip.split(".");
  j = ipArray.length
  if(j!=4) {
   alert(aj_IP); 
   return false;
  }  
  for(var i=0;i<4;i++) {
   if(ipArray[i].length==0 || ipArray[i]>255) {
    alert(aj_IP); 
    return false;
   }  
  }return true;   
 } 
}
 
function CheckMac(str)  
{  
//mac地址正则表达式  
var reg_name=/[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}/;  
if(!reg_name.test(str)){  
//alert("MAC地址格式不正确,MAC地址格式为00:24:21:19:BD:E4");  
return false;  
}  
return true;  
}   
  
function isEnglish(name){ //英文值检测 
	if(name.length == 0)
		return false;
	for(i = 0; i < name.length; i++) { 
		if(name.charCodeAt(i) > 128)
		return false;
	}
	return true;
}

function isChinese(name){//中文值检测
	if(name.length == 0)
		return false;
	for(i = 0; i < name.length; i++) { 
		if(name.charCodeAt(i) > 128)
		return true;
	}
	return false;
}

function isEmail(el)//用正则表达式判断
{
	var regu = "^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*[0-9a-zA-Z-]+))@([a-zA-Z0-9-]+[.])+([a-zA-Z]|net|NET|asia|ASIA|com|COM|gov|GOV|mil|MIL|org|ORG|edu|EDU|int|INT|cn|CN|cc|CC)$"
	var re = new RegExp(regu);
	if(el.search(re) == -1)
	{
		return true; //非法
	}
		return false;//正确
}



function isNumber(name) //数值检测
{ 
	if(name.length == 0)
	return false;
	for(i = 0; i < name.length; i++) { 
	if(name.charAt(i) < "0" || name.charAt(i) > "9")
		return false;
	}
	return true;
}
function isNumberToo(number) //数值检测 正负数均可
{ 
	if(number.length == 0)
	return false;
	var reg = /[^0-9-]/g;   
	if(reg.test(number)) return false;
	else return true;  
}
//判断用户名是否为数字字母下滑线 
//--------------------------------------- 
function notchinese(str){ 
	var reg=/[^@A-Za-z0-9_-]/g 
	if (reg.test(str)){ 
		return (false); 
	}else{ 
		return(true); 
	} 
} 
function notnumletters(str){ 
	var reg=/[^A-Za-z0-9]/g 
	if (reg.test(str)){ 
		return (false); 
	}else{ 
		return(true); 
	} 
}
//判断是否是电话号码
//--------------------------------------- 
//函数名：fucCheckTEL 
//功能介绍：检查是否为电话号码 
//参数说明：要检查的字符串 
//返回值：1为是合法，0为不合法 
function fucCheckTEL(TEL) 
{ 
var i,j,strTemp; 
strTemp="0123456789-()# "; 
for (i=0;i<TEL.length;i++) 
{ 
j=strTemp.indexOf(TEL.charAt(i)); 
if (j==-1) 
{ 
//说明有字符不合法 
return 0; 
} 
} 
//说明合法 
return 1; 
}  

//***************************************************
//表单判断
//(一)，项目表单
function checkProjectForm(){
	if(!isIP(myform.beginip.value))	return false;	
	if(!isIP(myform.endip.value)) return false;
	if(myform.userprefix.value !='' && !notchinese(myform.userprefix.value)){//用户前缀匹配(@ 0-9 a-z _ -)
		 alert(aj_pj_userprefix);
		 return false;
	}
	if(myform.userstart.value !='' && !isNumber(myform.userstart.value)){//用户开始ID必须为数字
	    alert(aj_pj_userstart);
	    return false;	
	}
	if( myform.userpwd.value !='' && !notchinese(myform.userpwd.value)){//用户密码匹配(@ 0-9 a-z _ -)
	  	 alert(aj_pj_userpwd);
		 return false;
	} 
	if(myform.name.value==""){
		alert(aj_pj_name_null);//项目名称不能空
		return false;
	}  
	if(!isNumber(myform.installcharge.value) || !isNumber(myform.mtu.value) || !isNumber(myform.accounts.value) || parseInt(myform.accounts.value) ==0  ){//安装非或记账包必须为数字
		alert(aj_pj_mtu_num);//安装非或记账包必须为数字
		return false; 
	} 
	if( myform.device.value =="mikrotik" ){		
	  var yes =document.myform.status[0].checked; 
	  var rem =document.myform.remind[0].checked; 
	  var due =document.myform.duestatus[0].checked; 
	  if(yes==true){
		if(!isIP(myform.rosip.value))	return false;
		if(myform.username.value=='' || !notchinese(myform.username.value)){
			alert(aj_pj_name_illegal);//用户名不合法
			return false;
		}
		var str1 = myform.inf.value;
        var s = str1.indexOf(' '); //接口中含有空字符
		if(myform.inf.value=='' || s!=-1){
			alert(aj_pj_name_inf);//接口输入有误
			return false;
		} 
		
	  }
	  if(rem==true){
		  if(myform.ippoolID.value =="" || !isNumber(myform.ippoolID.value) || myform.ippoolID.value ==0){
			 alert(aj_pj_name_pool_name);//请选择有效的地址池
			 return false;
		 }
		 if(myform.days.value =="" || !isNumber(myform.days.value)){
			alert(aj_pj_name_days);//到期天数输入有误
			return false;
		 } 
	  }
	  if(due==true){//到期分配地址池
	  	if(myform.duepoolID.value =="" || !isNumber(myform.duepoolID.value) || myform.duepoolID.value ==0){
			 alert(aj_pj_name_pool_name);//请选择有效的地址池
			 return false;
		  }
	  }
	  
	  if(myform.poolname.value.indexOf("#") != -1){
			 alert("地址池名不能出现'#'");
			 return false; 
		} 
	} 
  if( myform.device.value =="sla-profile" ){
  	var confstatus=document.myform.confstatus[0].checked;
  	if(confstatus==true){//启用通告
  		if(myform.noticetime.value=="" || !isNumber(myform.noticetime.value) || myform.noticetime.value<1){  
  			alert("到期通告时间不能为空且必须为正整数");return false;
  	} 
  	 if(myform.confname.value==""){
  	 	  alert("配置名称不能为空");return false;
  	 }  
  	} 
  } 
}
//地址池
function checkPoolForm(){
	if(!isIP(myform.beginip.value))	return false;	
	if(!isIP(myform.endip.value)) return false;
	if(myform.name.value==""){
		alert(aj_pj_name_pool_null);//地址池名称不能空
		return false;
	} 
}

//(二)，产品表单

function checkProductForm(){ 
	if(myform.name.value==""){
		alert(aj_pd_name_null);//产品名称不能为空
		return false;
	}
	if(myform.type.value=="flow"){ 
		 if(myform.periodflow.value==""){
	 	   alert(aj_pd_period_null);//产品计费周期不能为空
		   return false;
 	    }
	} else{ 
	    if(myform.periodOther.value=="" && myform.type.value!="netbar_hour"){
	 	   alert(aj_pd_period_null);//产品计费周期不能为空
		   return false;
 	    }
		
	}
	//if(!isNumber(myform.periodOther.value)){
//		alert(aj_pd_period_number);//"产品计费周期必须为数字"
//		return false;
//	}	
	 //if(myform.period.value==""){
//	 	alert(aj_pd_period_null);//产品计费周期不能为空
//		return false;
// 	}
//	if(!isNumber(myform.period.value)){
//		alert(aj_pd_period_number);//"产品计费周期必须为数字"
//		return false;
//	}	
	if(myform.price.value==""){
		alert(aj_pd_price_null);//"产品价格不能为空"
		return false;
	}if(myform.freeProduct.value=="" &&  parseInt(myform.price.value)==0){
		alert(aj_pd_freeProduct);//没有建立免费产品的权限
		return false;
	}
	if(!isNumber(myform.price.value)){
		alert(aj_pd_price_number);//"产品价格必须为数字"
		return false;
	}
	if(!isNumber(myform.penalty.value) && !myform.price.value==""){
		alert(aj_pd_penalty_number);//"产品违约金必须为数字"
		return false;
	}
	
	if(!isNumber(myform.creditline.value) && myform.creditline.value!=''){
		alert(aj_pd_creditline_number);//"信用额度必须为数字"
		return false;
	}
	if(!isNumber(myform.upbandwidth.value) && myform.upbandwidth.value!=''){
		alert(aj_pd_upload_number);//"上传速率必须为数字"
		return false;
	}	
	if(!isNumber(myform.downbandwidth.value) && myform.downbandwidth.value!=''){
		alert(aj_pd_download_number);//"下载速率必须为数字"
		return false;
	}	 
	
}

//(三)，用户表单 单用户添加
function checkUserForm(){
	var childVal=document.getElementById('actchild');
	if(childVal!=null){
		if(myform.actchild.value=='child'){
			if(myform.Mname.value==""){
				alert(aj_user_parentsAccount_null);	//"您的母账号不能为空"
				return false;
			}
			if(myform.Hchild.value=='0'){
				alert(aj_user_parentsAccount_exist);//"您的填写的母账号不存在"
				return false;
			}
			if(myform.Musercheck.value=='0'){
				alert(aj_user_parentsAccount_sub);	//"请确认母账号不是其他账号的子账号"
				return false;
			}
		 }
	 }
	  if(parseInt(myform.addusernum.value)>=parseInt(myform.addusertotalnum.value)){
			alert(aj_user_totalNum);	//"添加用户已达到上线,请联系管理员"
			return false;
		}if(parseInt(myform.totalMoney.value)>=parseInt(myform.manager_totalmoney.value)){
			alert(aj_user_totalMoney);	//"添加用户已达到上线,请联系管理员"
			return false;
		}
		
		/*if(myform.financeID.value !='' && !isNumber(myform.financemoney.value) ){//当缴费科目不为空时  缴费金额非法报错 /////-----2014.05.20修改 缴费科目可以为负数
		   alert(aj_user_financemoney);
		   return false;
		}*/
		
		if(myform.account.value==""){
			alert(aj_user_account_null);//	"您的帐号不能为空"
			return false;
		}
		if(myform.name.value==""){
			alert(aj_user_name_null);
			return false;
		}
		
		if(myform.account.value.indexOf("#")>=0 || myform.account.value.indexOf("&")>=0){
			 alert(aj_user_account_disabled);//"您的帐号含违禁字符# 或 &"
			 return false;
		}
		
		//if(!notchinese(myform.account.value)) {
			//alert("您的帐号不合法");
			//return false;
		//} 
		if(myform.usercheck){
		  if(myform.usercheck.value=="1"){
			alert(aj_user_account_exists);//"您的帐号系统中已经存在"
			return false;
		  }	
		}else{
			alert("请点击用户账号，判断该账号是否存在");//"您的帐号系统中已经存在"
			return false;
		} 
		if(document.getElementById("password").value==""){
			alert(aj_user_pwd_null);//"您的密码不能为空"
			return false;
		}  
		 if(!notnumletters(myform.password.value)) { 
			 alert(aj_user_pwd_numletters);//密码只能是数字或字母
			 return false;
		 } 
		if(myform.password.value != myform.pwd.value){
			alert(aj_user_pwd_not_consistent);	//"您输入的两次密码不一致"
			return false;
		} 　	 
		if(myform.projectID.value==""){
			alert(aj_user_project_null);//"您的帐号项目不能为空"
			return false;
		}
		
		if(myform.mobile.value==""){
			alert(aj_user_phoneNum_null);	//"您的手机号码不能为空"
			return false;
		}
		if(myform.address.value==""){
			alert(aj_user_addr_null);//"联系地址不能为空"	
			return false;
		}
		if(myform.productID.value==""){
			alert(aj_user_product_select);//"请您选择产品"
			return false;
		}
		if(!isNumber(myform.installcharge.value)){
			alert(aj_user_installation_fees_number);//"初装费必须为数字"
			return false; 
		}
                
		if(!isNumber(myform.money.value)){
			alert(aj_user_money_number);//"金额必须为数字"
			return false;
		}
	   if(myform.status.value=="enable" && myform.device.value=="mikrotik"){  
		  if(myform.iptype[0].checked==true){  
			  alert(aj_user_project_ros_IP);//"所选项目启用ROSIP认证，请分配IP" 
			  return false;
		  }else{
			  if(myform.ipaddress.value==''){
				alert(aj_user_IP_null);  //"IP不能为空"
			  }else if(!isIP(myform.ipaddress.value)){ 
				return false;  
			  }
		  }
		 if(!CheckMac(myform.account.value)){
			 alert(aj_user_account_MAC);  //"用户名格式不正确,用户名格式为00:24:21:19:BD:E4即用户MAC地址"
			  return false;  
	      }
		}//---------------------------
		
		/*if(!isNumber(myform.mobile.value) ||  myform.mobile.value.length!=11 ){
			alert("手机号码必须为11位数字");
			return false;
		}*/
            ////身份证号码验证，当选择证件类型为省份证时type=1 对身份证进行验证 2014.07.17
               if(myform.type.value=="1"){   
                var idcard =  myform.cardid.value; 
                var Errors=new Array(
                    "验证通过!",
                    "身份证号码位数不对!",
                    "身份证号码出生日期超出范围或含有非法字符!",
                    "身份证号码校验错误!",
                    "身份证地区非法!"
                    );
                    var area={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"}
                    var idcard,Y,JYM;
                    var S,M;
                    var idcard_array = new Array();
                    idcard_array = idcard.split("");
                    //地区检验
                    if(area[parseInt(idcard.substr(0,2))]==null){ 
                        alert(Errors[4]);
                                return false; 
                            }
                    //身份号码位数及格式检验
                    switch(idcard.length){
                    case 15:
                    if ( (parseInt(idcard.substr(6,2))+1900) % 4 == 0 || ((parseInt(idcard.substr(6,2))+1900) % 100 == 0 && (parseInt(idcard.substr(6,2))+1900) % 4 == 0 )){
                    ereg=/^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}$/;//测试出生日期的合法性
                    } else {
                    ereg=/^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}$/;//测试出生日期的合法性
                    }
                    if(ereg.test(idcard)){ 
                        //alert(Errors[0]);
                    return true;
                }
                    else{ 
                        alert( Errors[2]);
                    return false; 
                } 
                    break;
                    case 18:
                    //18位身份号码检测
                    //出生日期的合法性检查
                    //闰年月日:((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))
                    //平年月日:((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))
                    if ( parseInt(idcard.substr(6,4)) % 4 == 0 || (parseInt(idcard.substr(6,4)) % 100 == 0 && parseInt(idcard.substr(6,4))%4 == 0 )){
                    ereg=/^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}[0-9Xx]$/;//闰年出生日期的合法性正则表达式
                    } else {
                    ereg=/^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}[0-9Xx]$/;//平年出生日期的合法性正则表达式
                    }
                    if(ereg.test(idcard)){//测试出生日期的合法性
                    //计算校验位
                    S = (parseInt(idcard_array[0]) + parseInt(idcard_array[10])) * 7
                    + (parseInt(idcard_array[1]) + parseInt(idcard_array[11])) * 9
                    + (parseInt(idcard_array[2]) + parseInt(idcard_array[12])) * 10
                    + (parseInt(idcard_array[3]) + parseInt(idcard_array[13])) * 5
                    + (parseInt(idcard_array[4]) + parseInt(idcard_array[14])) * 8
                    + (parseInt(idcard_array[5]) + parseInt(idcard_array[15])) * 4
                    + (parseInt(idcard_array[6]) + parseInt(idcard_array[16])) * 2
                    + parseInt(idcard_array[7]) * 1
                    + parseInt(idcard_array[8]) * 6
                    + parseInt(idcard_array[9]) * 3 ;
                    Y = S % 11;
                    M = "F";
                    JYM = "10X98765432";
                    M = JYM.substr(Y,1);//判断校验位
                    if(M == idcard_array[17]){ return true;} //检测ID的校验位
                    else{ 
                        alert(Errors[3]); 
                           return false;  
                             }
                    }
                    else{
                        alert(Errors[2]);
                        return false; 
                    } 
                    break;
                    default:
                    alert(Errors[1]);return false; 
                    break;
                    }
               }
			
		
		if(myform.installcharge_type[0].checked==true || myform.installcharge_type[1].checked==true){
			if(myform.financeID.value!='') finmoney = Number(myform.financemoney.value) ; else finmoney = 0;
		 
			if(document.getElementById('productPrice')){
			totalMoney=Number(myform.productPrice.value)*Number(myform.period.value)+Number(myform.installcharge.value) +finmoney;  
			if(Number(myform.money.value)<totalMoney){
				alert(aj_user_installation_fees+myform.installcharge.value+","+aj_user_period+Number(myform.period.value)+","+aj_user_finmoney+":"+finmoney);//"您所预存的金额不足支付所选择的产品的价格和初装费用，初装费用为：xxx ,订单数为： ,最低预存金额为："+","+aj_user_limit_money+totalMoney  缴费金额  aj_user_finmoney
				  return false;
			}	
		}else{
			 alert(aj_product_price_null);//暂未获取到产品价格,请重新选择产品 
			 return false;
		}
		}  

//	if(isEmail(myform.email.value)){
//		alert("邮件地址不合法");
//		return false;
//	}
}

//多用户添加
function checkUserMoreForm(){ 
  if(myform.prefix.value==""){
		alert(aj_user_prefix_null);	//"输入的标识符不能为空"
		return false;
	}
	if(myform.prefix.value.indexOf("#")>=0 || myform.prefix.value.indexOf("&")>=0){
	    alert("您的帐号含违禁字符# 或 &");
	    return false;
  }
  if(myform.startID.value==""){
		alert(aj_user_start_ID_null);//"开始ID不能为空"
		return false;
	}
  if(!isNumber(myform.startID.value) || myform.startID.value<1){
		alert(aj_user_start_ID_more_than1);//"开始ID必须为数字且大于等1"
		return false;
	}
  if(myform.endID.value==""){
		alert(aj_user_end_ID_null);//"结束ID不能为空"
		return false;
	}
  if(!isNumber(myform.endID.value) || myform.endID.value<1){
		alert(aj_user_end_ID_more_than1);//"结束ID必须为数字且大于等1"
		return false;
	}
  if(myform.endID.value==myform.startID.value){
		alert(aj_user_start_end_ID_same);//"开始ID与结束ID不能相同"
		return false;
	}
  if(parseInt(myform.endID.value)<parseInt(myform.startID.value)){
		alert(aj_user_start_end_ID_little);//"开始ID必须小于结束ID"
		return false;
	}
	if(myform.projectID.value==""){
		alert(aj_user_project_null);//"您的帐号项目不能为空"	
		return false;
	}
	if(myform.status.value=="enable"){  
		alert(aj_user_project_ros); //"所选项目启用ROSIP认证，需分配IP，不允许批量添加"
		return false;
	}
	if(myform.productID.value==""){
		alert(aj_user_product_select);//"请您选择产品"
		return false;
	}
	if(!isNumber(myform.installcharge.value)){
		alert(aj_user_installation_fees_number);//"初装费必须为数字"	
		return false; 
	}
	if(!isNumber(myform.money.value)){
		alert(aj_user_money_number);//"金额必须为数字"
		return false;
	}
  if(myform.installcharge_type[0].checked==true || myform.installcharge_type[1].checked==true){
		totalMoney=Number(myform.productPrice.value)+Number(myform.installcharge.value);
		if(Number(myform.money.value)<totalMoney){
			alert(aj_user_installation_fees+myform.installcharge.value);"您所预存的金额不足支付所选择的产品的价格和初装费用，初装费用为："
			return false;
		}	
	}else{		
		if(Number(myform.money.value)<Number(myform.productPrice.value)){
			alert(aj_user_money_enough);//"您所预存的金额不足支付所选择的产品的价格"
			return false;
		}	
	}
	
}
//时长计费用户添加提交
function checkUserNetbarForm(){
 if(myform.projectID.value==""){
	alert(aj_user_project_null);//"您的帐号项目不能为空"
	return false;
 }
 if(myform.account.value==""){
    alert(aj_user_account_null);//	"您的帐号不能为空"
	return false;
 }
 if(!notnumletters(myform.cardid.value) && myform.cardid.value !=''){
	 alert("证件号只能是数字或字母");
	 return false;
 }
 if(myform.usercheck.value=="2"){
    alert(aj_user_account_disabled);//"您的帐号含违禁字符# 或 &"
    return false;
 } 

 if(myform.usercheck.value=="1"){
    alert(aj_user_account_exists);//"您的帐号系统中已经存在"
    return false;
 }	

 if(myform.password.value==""){
    alert(aj_user_pwd_null);//"您的密码不能为空"
    return false;
 }  
 if(!notnumletters(myform.password.value)) { 
    alert(aj_user_pwd_numletters);//密码只能是数字或字母
    return false;
 }
 if(myform.password.value != myform.pwd.value){
    alert(aj_user_pwd_not_consistent);	//"您输入的两次密码不一致"
    return false;
 }
 if(myform.productID.value==""){
	alert(aj_user_product_select);//"请您选择产品"
    return false;
 }　 
 //if(myform.limittype[1].checked==true){
 if(myform.limittype.checked==true){
	 if(myform.money.value <0 || myform.money.value ==0){
	  alert(aj_user_monery_zero);//"金额必须》0"
      return false;
	  }
	 if(!isNumber(myform.money.value)){
		alert(aj_user_money_number);//"金额必须为数字"
		return false;
	 }
  }
}

//管理员修改密码
function checkManagerFrom(){
	if(myform.oldpwd.value!=myform.manager_passwd.value){
		alert(aj_manager_old_pwd_error);//'旧密码输入有误'
		return false; 
	}
	if(!notnumletters(myform.newpwd.value)) { 
		 alert(aj_user_pwd_numletters);//密码只能是数字或字母
		 return false;
	}
	if(myform.newpwd.value=='') { 
			 alert(aj_user_pwd_null);//您的密码不能为空
			 return false;
	}if(myform.newpwd.value!=myform.newpwd1.value){
		alert(aj_manager_old_new_pwd_match);//'新密码两次输入不匹配'
	   	return false;
    }
}
//人工收费添加 
function checkMTCForm(){
 if(myform.account.value==''){
	alert(aj_finance_MTC_username_null);//'用户名不能为空' 
	return false;
 }
 if(myform.userName.value==''){
	 alert(aj_finance_MTC_username_exist);//'输入的用户名不存在，请确认'
	 return false;
 } 
 if(myform.methods.value==''){
	 alert(aj_finance_MTC_type);//'请选择支付类型'
	 return false;
 }
 if(myform.methods.value==0 && Number(myform.E_money.value)< Number(myform.money.value)){
     alert(aj_finance_MTC_type_cash);//'余额不足以支付，可选择现金支付'
	 return false;
 }
 
 if(myform.financeID.value==''){
	 alert(aj_finance_MTC_project_name);//'请选择收费科目'
	 return false; 
 }

  if(myform.money.value!='' && !isNumber(myform.money.value)){
	 alert(aj_finance_MTC_money_number);//'缴费金额必须为数字'
	 return false; 
 }
 if(Number(myform.money.value)<=0){
	 alert(aj_finance_MTC_money_more_than0);//'缴费金额必须大于0'
	 return false; 
 }
}

function messageForm(){
	if(myform.status[0].checked && ( myform.days.value<1 || myform.days.value >15 || !isNumber(myform.days.value)) ){
		alert("提前到期时间不合法,1-15的正整数!");//"您的帐号不能为空"
		return false;
	}
}
//增加订单表单
function checkOrderForm(){
	var btn = document.getElementById("submitBtn");
	btn.disabled="disabled";
	if(myform.account.value==""){
		alert(aj_user_account_null);//"您的帐号不能为空"
		btn.disabled="";
		return false;
	}
	if(myform.userID.value==""){
		alert(aj_finance_MTC_username_exist);//输入的用户名不存在，请确认      "系统中不存在您所输入的帐号，请确定您是否是已存在用户"
		btn.disabled="";
		return false;
	} 
	if(myform.rechange.value==1){//卡片充值  
		if(myform.card_num.value==""){//充值卡号不能为空
			alert(aj_user_card_num); 
		    btn.disabled="";
			return false;
		} 
		if(myform.cardErr.value=="1"){//充值卡号不存在
			alert(aj_user_cardErr);//"充值卡号不存在"
		    btn.disabled="";
			return false;
		}
		if(myform.cardLost.value=="1"){//充值卡号已经被充值
			alert(aj_user_cardLost);//"充值卡号已经被充值"
		    btn.disabled="";
			return false;
		} 
		if(myform.card_pwd.value==""){//充值卡号密码不能为空
			alert(aj_user_card_pwd);
		    btn.disabled="";
			return false;
		}
		if(myform.card_pwd.value!=myform.password.value){//密码不正确
            alert(aj_user_card_pwd_err);
		    btn.disabled="";
			return false;
		}
    }
	if(myform.recharge_money.value==""){
		alert(aj_order_add_money_null);//"金额不能为空"
		btn.disabled="";
		return false;
	}
	if(!isNumberToo(myform.recharge_money.value)){
		alert(aj_user_money_number);//"金额必须为数字"
		btn.disabled="";
		return false;
	} 
	if(myform.isrechange.value=="yes"){
		if(myform.productID.value==""){
			alert(aj_user_product_select);//"请您选择产品"
			btn.disabled="";
			return false;
		}
	}	 
	if(Number(myform.orderCount.value) >=12){
		alert(aj_user_orderCount);//"该用户已有12个订单, 如需续费，请将该订单撤销后方可进行本次操作"
		btn.disabled="";
		return false;
    }
	if(myform.period.value==''){
		alert(aj_user_period_null);//"续费周期必填"
		btn.disabled="";
		return false;
    }
	if((Number(myform.orderCount.value) + Number(myform.period.value)) >12 ){
		alert(aj_user_have_order+Number(myform.orderCount.value) + aj_user_maxadd_order+(12-Number(myform.orderCount.value)));//"当前用户已有订单数:最多可续费订单为:"
		btn.disabled="";
		return false;   
	}
	totalMoney=Number(myform.money.value)+Number(myform.recharge_money.value);
    if(myform.rechange.value==1) //卡片充值
		totalMoney=Number(myform.money.value)+Number(myform.recharge_money.value)+Number(myform.rechangeMoney.value) ;//余额+充值金额+卡片金额
	 
	if(totalMoney<Number(myform.productPrice.value)*Number(myform.period.value)){
		alert(aj_user_money_enough+Number(myform.productPrice.value)*Number(myform.period.value));//"您所预存的金额不足支付所选择的产品的价格"
	    btn.disabled="";
		return false; 
	}
	return true; 
}
//充值表单
function checkRechargeForm(){  
	if(myform.account.value==""){
		alert(aj_user_account_null);//"您的帐号不能为空"
		return false;
	}
	if(myform.userID.value==""){
		alert(aj_finance_MTC_username_exist);//输入的用户名不存在，请确认      "系统中不存在您所输入的帐号，请确定您是否是已
		return false;
	}   
	 
	if(myform.rechange.value==0){//现金充值
	  if(!isNumber(myform.recharge_money.value)){
		   alert(aj_user_money_number);//"金额必须为数字"
		   return false;
     }	 
 	 if(Number(myform.recharge_money.value)<=0){
		alert(aj_recharge_money_not0);//"您所充值的费用不能为0"
		return false;
	 }	
   }else{//卡片充值 
      if(myform.card_num.value==""){//充值卡号不能为空
  	    alert(aj_user_card_num); 
		    return false;
	   } 
	   if(myform.cardErr.value=="1"){//充值卡号不存在
  	    alert(aj_user_cardErr);//"充值卡号不存在"
		    return false;
	   }
	  if(myform.cardLost.value=="1"){//充值卡号已经被充值
  	    alert(aj_user_cardLost);//"充值卡号已经被充值"
		    return false;
	   } 
	  if(myform.card_pwd.value==""){//充值卡号密码不能为空
  	    alert(aj_user_card_pwd);
		    return false;
	   }
	   if(myform.card_pwd.value!=myform.password.value){//密码不正确
            alert(aj_user_card_pwd_err);
		    return false;
		}
	    
      } //end 卡片充值  
}

//用户充帐表单 
function checkReverseForm(){
	if(myform.account.value==""){
		alert(aj_user_account_null);//"您的帐号不能为空"	
		return false;
	}
	if(myform.userID.value==""){
		alert(aj_finance_MTC_username_exist);//输入的用户名不存在，请确认      "系统中不存在您所输入的帐号，请确定您是否是已
		return false;
	}
	if(myform.money.value==""){
		alert(aj_order_add_money_null);//"金额不能为空"
		return false;
	}	
	if(!isNumber(myform.money.value)){
		alert(aj_user_money_number);//"金额必须为数字"
		return false;
	}	
	if(Number(myform.money.value)<=0){
		alert(aj_reverse_money_0);//"您要冲帐金额小于等于零没有实际意义"
		return false;
	}		
	
}

//用户帐号申请停机，恢复
function checkShutdownForm(){
	if(myform.account.value==""){
		alert(aj_user_account_null);//"您的帐号不能为空"
		return false;
	}
	if(myform.userID.value==""){
		alert(aj_finance_MTC_username_exist);//输入的用户名不存在，请确认      "系统中不存在您所输入的帐号，请确定您是否是已
		return false;
	}
	if(myform.methods.value==''){
	 alert(aj_finance_MTC_type);//'请选择支付类型'
	 return false;
   }
   if(!isNumber(myform.money.value)  && myform.money.value!=""){
		alert(aj_user_money_number);//"金额必须为数字"
		return false;
	}
	if(myform.closing.value=="1"){
		alert(aj_closing_type);//	"您的用户已经销户，不能对它进行操作"
		return false;
	}
}	
function checkRefundForm(){
	if(myform.account.value==""){
		alert(aj_user_account_null);//"您的帐号不能为空"
		return false;
	}
	if(myform.userID.value==""){
		alert(aj_finance_MTC_username_exist);//输入的用户名不存在，请确认      "系统中不存在您所输入的帐号，请确定您是否是已
		return false;
	}
	if(!isNumber(myform.money.value) && myform.money.value!=""){
		alert(aj_user_money_number);//"金额必须为数字"
		return false;
	}
	if(myform.methods.value==''){
	 alert(aj_restore_refund_type);//'请选择支付类型'
	 return false;
   } 
/*	if(myform.restoredatetime.value!="0000-00-00 00:00:00"){
		alert(myform.stopdatetime.value);
		if(myform.stopdatetime.value>myform.restoredatetime.value){
			alert("设置的恢复时间不能小于暂停时间");	
			return false;
		}
	}*/

	if(typevalue==myform.status.value){
		alert(aj_closing_more_than);//"请不要进行重复的操作"
		return false
	}
}

//用户移机
function checkUserMoveForm(){
	if(myform.account.value==""){
		alert(aj_user_account_null);//"您的帐号不能为空"	
		return false;
	}
	if(myform.money.value==""){
		alert(aj_order_add_money_null);//"金额不能为空"
		return false;
	}	
	if(!isNumber(myform.money.value)){
		alert(aj_user_money_number);//"金额必须为数字"
		return false;
	}		
}

//用户更换产品
function checkUserReplacProductForm(){
	if(myform.account.value==""){
		alert(aj_user_account_null);//"您的帐号不能为空"	
		return false;
	}
	if(myform.userID.value==""){
		alert(aj_finance_MTC_username_exist);//输入的用户名不存在，请确认      "系统中不存在您所输入的帐号，请确定您是否是已
		return false;
	}

	if(myform.orderID.value==""){
		alert(aj_product_change_order_add);//"此帐号目前系统中不存正在使用的订单，需新添加一条新的产品订单"
		return false;
	}
	if(myform.checkProductID.value==""){
		alert(aj_user_product_select);	//"请您选择产品"
		return false;
	}
	if(!isNumberToo(myform.bjmoney.value)){
		alert("费用必须为数字");	//"请您选择产品"
		return false;
	}
	
	/*
	if(Number(myform.surplusToalMoeny.value)<Number(myform.productPrice.value)){
		alert(aj_product_change_money_notenough+myform.surplusToalMoeny.value);//"您所预存的金额不足支付所选择的产品的价格,您当前帐号余额加产品退返金额共："
		return false;
	}	
	*/	
}


//用户销户
function checkUserClosingForm(){
	if(myform.account.value==""){
		alert(aj_user_account_null);//"您的帐号不能为空"
		return false;
	}  
	if(Number(myform.closing.value)==1){ 
		alert(aj_user_closing_type);//"你把输入的用户已经销户，请不要重复操作"
		return false;
	}
	if(myform.userID.value==""){
		alert(aj_finance_MTC_username_exist);//输入的用户名不存在，请确认      "系统中不存在您所输入的帐号，请确定您是否是已
		return false;
	}
	if(myform.closedatetime.value!="0000-00-00 00:00:00"){
		alert(aj_user_closing_endtime);	//"您所操作的用户已经销户过了，请不要重复操作"
		return false;
	}
	if(!isNumber(myform.penalty.value) && myform.penalty.value!=''){
		alert(aj_user_money_number);//"金额必须为数字"
		return false;
	}
	if(!isNumber(myform.factmoney.value)){
		alert(aj_user_money_number);//"金额必须为数字"
		return false;
	}	
	if(Number(myform.factmoney.value)<0){
		alert(aj_closing_factmoney);//"实退金额不能小于 0"
		return false;	
	}
	if(myform.orderID.value!=""){
		if(!confirm(aj_closing_order_status_0)) {//'此帐号目前存在一条未执行完的订单，是否销户'
			return false; 
		}
	}
	
	if(Number(myform.surplusToalMoeny.value)<0){
		alert("111111111");
		if(!confirm(aj_closing_user_owe)){ //'帐号欠的有费用你真的确定要销户'
			return false; 
		}else{
			if(Number(myform.factmoney.value)>0){
				alert(aj_closing_factmoney);//账号已欠费不能退费  "您的帐号现在都欠费了还想退钱~门都没有"
				return false;	
			}			
		}
	}else{ 
		 if(myform.penaltyStatus[0].checked){
			 var sum = Number(myform.surplusToalMoeny.value) - Number(myform.penalty.value); 
			if(sum<Number(myform.factmoney.value)){
				alert(aj_closing_factmoney_over+":"+sum);//"实际退费金额不能超过应退金额"	
				return false;
			}
		 }else{
			if(Number(myform.surplusToalMoeny.value)<Number(myform.factmoney.value)){
				alert(aj_closing_factmoney_over);//"实际退费金额不能超过应退金额"	
				return false;			
		   } 
		}
			
	}
}



//卡片生成表单
function checkCardForm(){
	if(myform.prefix.value==""){
		alert(aj_card_prefix_null);//"卡片的前缀不能为空"
		return false;
	}
	if(myform.startNum.value==""){
		alert(aj_card_start_null);//"卡片的起始编号不能为空"
		return false;
	}	
	if(!isNumber(myform.startNum.value)){
		alert(aj_card_start_number);//"起始编号必须是0-9之间数字"
		return false;
	}	
	if(myform.endNum.value==""){
		alert(aj_card_end_null);//"卡片的结束编号不能为空"
		return false;
	}
	if(!isNumber(myform.endNum.value)){
		alert(aj_card_end_null);//"结束编号必须是0-9之间数字"
		return false;
	}	
	if(Number(myform.endNum.value)<Number(myform.startNum.value)){
		alert(aj_card_start_small_end);//"卡片的结束编号不能小于起始编号"
		return false;
	}
	if(!isNumber(myform.money.value)){
		alert(aj_user_money_number);//"金额必须为数字"
		return false;
	}	
	if(myform.money.value<=0){
		alert(aj_card_money);//"金额必须不能小于等于0"
		return false;
	}	
}


function checkRepairAddForm(){
	if(myform.userID.value==""){
		alert(aj_finance_MTC_username_exist);	//输入的用户名不存在，请确认 "系统中不存在此用户名"   
		return false;
	}
	if(myform.reason.value==""){
		alert(aj_repair_reason); //"请认真填写事件原因" 
		return false;
	}
}

function p_del() {
var msg = aj_dell+"\n\n"+aj_p_del;//   "您真的确定要删除吗？\n\n请确认！";//
	if (confirm(msg)==true){
		return true;
	}else{
		return false;
	}
}

//***********************************************


//在表单中的决断
function CheckForm()
{ 
if(!isMail(form.Email.value)) { 
alert(aj_e_mail);//"您的电子邮件不合法！"
form.Email.focus();
return false;
}
if(! isEnglish(form.name.value)) { 
alert(aj_en_name_illegal);//"英文名不合法！"
form.name.focus();
return false;
}
if(! isChinese(form.cnname.value)) { 
alert(aj_ch_name_illegal);//"中文名不合法！"
form.cnname.focus();
return false;
}
if(! isNumber(form.PublicZipCode.value)) { 
alert(aj_post_illegal);//"邮政编码不合法！"
form.PublicZipCode.focus();
return false;
}
return true;
}

//删除提示
function del(theURL)
{
 if(!confirm(aj_dell))//"您确定要删除吗？"
 {
  return ;
 }
 document.location.href=theURL;
} 

//管理员添加
function checkManagerAdd(){
   if(!notnumletters(myform.manager_passwd.value)) { 
		 alert(aj_user_pwd_numletters);//密码输入不能为中文
		 return false;
	}	
}

function checkCardSold(){
	//if(myform.UserName.value=='') { 
//		 alert(aj_user_account_null);//您的账号必须为空
//		 return false;
//	}
//	if(myform.userID.value==""){
//		alert(aj_finance_MTC_username_exist);//输入的用户名不存在，请确认      "系统中不存在您所输入的帐号，请确定您是否是已存在用户"
//		return false;
//	}
}
function checkDBautoForm(){
	if(!isNumber(myform.scannum.value)){
		 alert(db_auuto_scannum);//_("自动备份保留数必须为数字")
		 return false;
		}
	if(parseInt(myform.scannum.value)< 3 || parseInt(myform.scannum.value)> 20){
	   alert(db_auuto_scannum_three_twenty);//._("自动备份保留数范围3~20")
		 return false;
		}
	if(myform.ftp.checked==true){
		if(!checkIP(myform.ftp_ip.value)) return false;
		if(!isNumber(myform.ftp_port.value) ||  parseInt(myform.ftp_port.value)< 0 || parseInt(myform.ftp_port.value)> 65535 ){
	   alert(db_auuto_ftp_port);//._("输入的端口不合法")
		 return false;
		}
	  if(!notchinese(myform.ftp_username.value)){
	   alert(aj_pj_name_illegal);//._("用户名不合法")
		 return false;
		}if(!notchinese(myform.ftp_pwd.value)){
	   alert(aj_pj_userpwd);//._("密码匹配规则见 ajax_js.php")
		 return false;
		}if(myform.ftp_pwd.value != myform.ftp_pwd2.value ){
	   alert(aj_user_pwd_not_consistent);//._("输入的两次密码不一致")
		 return false;
		} 	  
  }
}
function checkSyncForm(){
 if(!checkIP(myform.ipaddress.value)|| myform.ipaddress.value==""  ) 
		 return false;  
}
function checkDBForm(){
	if(myform.status.value=="yes"){//开启同步
		 if(parseInt(myform.period.value) < 2 || !notchinese(myform.period.value)){
		 	  alert("请输入有效的周期时间");
		 	  return false; 
		 	}
		 if(myform.master_dbname.value==""  || !notchinese(myform.master_dbname.value)){
		 	  alert("请输入合法的主机名");
		 	  return false; 
		 }
		 if(!notchinese(myform.master_ipaddress.value))  return false;  
		 if(!notchinese(myform.master_username.value)){
			  alert("请输入合法的主机登录用户名");
		    return false; 
		  }
		 if(!notchinese(myform.master_pwd.value)){
		 	   alert("请输入合法的主机登录密码");
		 	   return false; 
		 }
		 //备用机1
		 if(myform.slave_dbname.value==""  || !notchinese(myform.slave_dbname.value)){
		 	  alert("请输入合法的备用机1名");
		 	  return false; 
		 }
		 if(!notchinese(myform.slave_ipaddress.value))  return false;  
		 if(!notchinese(myform.slave_username.value)){
			  alert("请输入合法的备用机1登录用户名");
		    return false; 
		  }
		 if(!notchinese(myform.slave_pwd.value)){
		 	   alert("请输入合法的备用机1登录密码");
		 	   return false; 
		 }
	   //备用机2
	  if(myform.slave_dbname_two.value!="" || myform.slave_ipaddress_two.value!="" ||myform.slave_username_two.value!="" || myform.slave_pwd_two.value!="" ){
		 if(myform.slave_dbname_two.value==""  || !notchinese(myform.slave_dbname_two.value)){
		 	  alert("请输入合法的备用机2名");
		 	  return false; 
		 }
		 if(!notchinese(myform.slave_ipaddress_two.value))  return false;  
		 if(!notchinese(myform.slave_username_two.value)){
			  alert("请输入合法的备用机2登录用户名");
		    return false; 
		  }
		 if(!notchinese(myform.slave_pwd_two.value)){
		 	   alert("请输入合法的备用机2登录密码");
		 	   return false; 
		 } 
		}//end 备用机2有一项不为空
	}//end status yes
}