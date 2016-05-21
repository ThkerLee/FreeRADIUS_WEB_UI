
<?
@include("../evn.php");
@include("evn.php");
?>
<script type="text/javascript">
//这是创建层的函数
function S(i) { return document.getElementById(i); }
function dowm( evt, fid){
var _event = evt ? evt : event;
var _target = evt ? evt.target : event.srcElement;
var _p = S( "downloadPanel" );
_p.style.top = _event.clientY + document.body.scrollTop ;
_p.style.left = ( _event.clientX + document.body.scrollLeft < 160 ? _event.clientX + document.body.scrollLeft + 10 : _event.clientX + document.body.scrollLeft - 120 );
Show( "downloadPanel" , true ); 
_p.focus();
}
function Show(obj, bShow) {
obj = (typeof(obj) == "string" ? S(obj) : obj);
if (obj) obj.style.display= (bShow ? "" : "none");
}
function hideDownloadPanel( evt ){
Show( "downloadPanel" ,false); 
}
function checkClick(evt){
var _target = evt ? evt.target : event.srcElement ;
var _id = _target.id;
if( _id == "" ){
_id = _target.parentNode.id;
}
if( _id !="downloadDirect" && _id != "downloadAgent" && _id != "downloadPanel" && _id.indexOf( "downloadFile_" ) < 0 && _id.indexOf( "downloadLink_" ) < 0 ){
Show( "downloadPanel" , false );
}
}
/*window.onload = function(){
document.body.onclick=checkClick;
}*/


//创建 XMLHttpRequest
//对象不同的浏览器使用不同的方法来创建 XMLHttpRequest 对象。
//Internet Explorer 使用 ActiveXObject。
//其他浏览器使用名为 XMLHttpRequest 的 JavaScript 内建对象。
function GetXmlHttpObject1()
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
function loaduser(pageName,dataName,sendValue,setValueId){  
	xmlhttp=GetXmlHttpObject1();
	url= ""+pageName+"?"+dataName+"="+escape(sendValue);
	xmlhttp.open("GET",url,true); 
	xmlhttp.send(null); 
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
</script>
<style type="text/css">
.infobar {
	background:#fff9e3;
	color:#743e04;
	line-height:20px;
}
.infobar a {
	color:#4d5d2c;
	text-decoration:underline;
	cursor:pointer;
	line-height:25px;
	font-size:14px;
}
.contentinfo a {
	color:#4d5d2c;
	text-decoration:underline;
	cursor:pointer;
	line-height:25px;
	font-size:12px;
}
</style>  
<div id="downloadPanel" style="position:absolute;top:0px;left:0px;width:620px;z-index:999;padding:5px 5px 5px 5px;border:1px solid #fb7;background:#fb7;display:none;">
  <table width="100%" border="0" cellspacing="2" cellpadding="5"  class="infobar">
    <tr>
      <td width="68%"><? echo _("用户详细信息");?></td>
      <td width="12%" align="right"><a onClick="hideDownloadPanel();"><? echo _("关闭");?></a></td>
    </tr>
    <tr>
      <td colspan="2" id="loadusershow" class="contentinfo"></td>
    </tr>
  </table>
</div>
