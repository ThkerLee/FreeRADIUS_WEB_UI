
function is_IP(value){//判断IP地址
  var pattern = /^\d{1,3}(\.\d{1,3}){3}$/;
  if (!pattern.exec(value)) {
    alert('请输入一个有效的IP地址');
    return false;
  }
  var ary = value.split('.');
  for(key in ary)
  {
    if (parseInt(ary[key]) > 255){
      alert('请输入一个有效的IP地址');
      return false;
    }
  }
  return true ;
}

function IsNum(num){
  var reNum=/^\d*$/;
  return(reNum.test(num));
}



function add(){

if(!is_IP(myform.fwd_ipaddr.value)){
	
		return false;
	}
if(!IsNum(myform.fwd_port.value)){
		alert('端口输入有误！');
		return false
		}
	return true;
}