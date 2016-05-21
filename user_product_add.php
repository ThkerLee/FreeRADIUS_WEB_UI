#!/bin/php
<html>
<?php
require_once("evn.php");
?>
<body>
<!-- 产品添加  -->
<script src='./js/jquery-1.4.js'> </script>
<style>
body{

background-color:#fff;}
#product
{color:lime;
display:none;
background:#fff;
position:absolute;
top:35;
left:50;
text-align:right;
}

#product td a:hover
{
color:blue;
}
</style>

<!--<div class=''>&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:add_product()'>产品添加</a>	</div>
-->
<table id="product" width="572" height="380px" border="0" cellpadding="0" cellspacing="0">

  <tr>

  <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">

		<table width="544" border="0" cellspacing="0" cellpadding="0">

		  <tr>

		<td width="19%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>

			<td width="81%" height="35" valign="middle"><font color="#FFFFFF" size="2">
			  <? echo _("产品管理")?></font></td>

		  </tr>

	  </table>

	</td>


  <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>

  </tr>

  

  <tr>

    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>

    <td height="350" valign="top" width="544"  style="background-color:#fff">

	<table width="100px" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">

      <tr>

        <td colspan="2" ><font style="text-align:left"><? echo _("产品添加")?></font></td>

		

      </tr>

	  </table>

   <form action="?" method="post" name="myform"  onSubmit="return checkProductForm();">

  	  <table width="96%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">

        <tbody>     

		  <tr>

			<td width="13%" align="right" class="bg"><? echo _("产品名称")?>：</td>

			<td width="20%" align="left" class="bg"><input type="text" id="name" name="name" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"></td>

		  </tr>

		  <tr>

		    <td align="right" class="bg"><? echo _("产品类型")?>： </td>

		    <td align="left" class="bg">

				<select name="type" onChange="product_type_change();">

					<option value="year"><? echo _("包年")?></option>

					<option value="month"><? echo _("包月")?></option>

					<option value="days"><? echo _("包天")?></option>

					<option value="hour"><? echo _("包时")?></option>

					<option value="flow"><? echo _("计流量")?></option>

				</select>

			</td>

	      </tr>

		  <tr>

		    <td align="right" class="bg"><? echo _("计费周期")?>：</td>

		    <td align="left" class="bg"><input type="text" id="period" name="period" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';"> <span id="periodTXT"></span></td>

	      </tr>

		  <tr>

		    <td align="right" class="bg"><? echo _("周期价格")?>：</td>

		    <td align="left" class="bg"><input type="text" id="price" name="price" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';"> <? echo _("元")?></td>

	      </tr>

		  <tr id="unitpriceTR">

		    <td align="right" class="bg"><? echo _("产品费率:")?></td>

		    <td align="left" class="bg"><input type="unitprice" name="unitprice" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> <span id="unitpriceTXT"></span></td>

	      </tr>

		  <tr id="cappingTR">

		    <td align="right" class="bg"><? echo _("封顶金额:")?></td>

		    <td align="left" class="bg"><input type="capping" name="capping" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> 元</td>

		    </tr>

		  <tr>

		    <td align="right" class="bg"><? echo _("信用额度:")?></td>

		    <td align="left" class="bg"><input type="text" name="creditline" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> <span id="creditlineTXT"></span></td>

	      </tr>

		  <tr>

		    <td align="right" class="bg"><? echo _("上传速率:")?></td>

		    <td align="left" class="bg"><input type="text" name="upbandwidth" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> kbit</td>

		    </tr>

		  <tr>

		    <td align="right" class="bg"><? echo _("下载速率:")?></td>

		    <td align="left" class="bg"><input type="text" name="downbandwidth" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> kbit </td>

		    </tr>

		  <tr>

		    <td align="right" class="bg"><? echo _("所属项目:")?></td>

		    <td align="left" class="bg">

			<input type="checkbox" name="allProjectID" onClick="changeAllProjectID();"><? echo _("所有项目")?><br><br>

			<?php 

				$auth_projectArr=explode(",",$auth_project);

				if(is_array($projectResult)){

					foreach($projectResult as $key=>$rs){

						if(in_array($rs["ID"],$auth_projectArr)){

						echo "<input type='checkbox' name='projectID[]' value='".$rs["ID"]."'> ".$rs["name"]." &nbsp;";

						}

					}

				}

			?>

			

			</td>

		    </tr>

		  <tr>

		    <td align="right" class="bg"><? echo _("产品描述:")?></td>

		    <td align="left" class="bg">

				<input type="text" name="description" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out">

			</td>

		    </tr>

		  <tr>

		    <td align="right" class="bg">&nbsp;</td>

		    <td align="left" class="bg">

				<input type="submit" value="<? echo _("提交")?>">			</td>

	      </tr>
			<tr>
			
			<td colspan=2><font color='red' style="padding-left:450px"><a href='javascript:add_product()'><? echo _("关闭")?></a></font></td>
			
			</tr>
        </tbody>      

    </table>	

</form>

	</td>

    <td width="14" background="images/li_r6_c14.jpg">&nbsp;</td>

  </tr>
<tr>

    <td width="14" height="14"><img name="li_r16_c4" src="images/li_r16_c4.jpg" width="14" height="14" border="0" id="li_r16_c4" alt="" /></td>

    <td width="400px" height="14" background="images/li_r16_c5.jpg"><img name="li_r16_c5" src="images/li_r16_c5.jpg" width="100%" height="14" border="0" id="li_r16_c5" alt="" /></td>

    <td width="14" height="14"><img name="li_r16_c14" src="images/li_r16_c14.jpg" width="14" height="14" border="0" id="li_r16_c14" alt="" /></td>

  </tr>

</table>
 



<script language="javascript">


window.onLoad=product_type_change();



function changeAllProjectID(){

	v=document.myform.allProjectID;

	m=document.getElementsByName("projectID[]");

	for(i=0;i<m.length;i++){

		m[i].checked=v.checked;

	}

}



function product_type_change(){

	v=document.myform.type.value;

	if(v=="year"){

		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>年</font>";

		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>天</font>";

		document.getElementById("unitpriceTR").style.display="none";

		document.getElementById("cappingTR").style.display="none";

	}else if(v=="month"){

		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>月</font>";

		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>天</font>";

		document.getElementById("unitpriceTR").style.display="none";

		document.getElementById("cappingTR").style.display="none";

	}else if(v=="days"){

		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>天</font>";

		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>天</font>";

		document.getElementById("unitpriceTR").style.display="none";

		document.getElementById("cappingTR").style.display="none";	

	}else if(v=="hour"){

		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>时/月</font>";

		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>元</font>";

		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'>元/时</font>";

		document.getElementById("unitpriceTR").style.display="block";

		document.getElementById("cappingTR").style.display="block";

	}else if(v=="flow"){

		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>M/月</font>";

		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>元</font>";

		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'>元/M</font>";

		document.getElementById("unitpriceTR").style.display="block";

		document.getElementById("cappingTR").style.display="block";

	}

	



}



var flag=true;
	function add_product()
	{
		if(flag)
		{
		$('#product').fadeIn(10);// fadeIn淡入    jQuery弹出效果淡入淡出（时间） 即多长时间完成淡入淡出  单位毫秒
		$('#product').css('display','block');flag=false;		
		}
		else
		 {
		 flag=true;
		 $('#product').fadeOut(10);//淡出
		 $('#product').css('display','none');//none隐藏
		 //hidden 占位隐藏 
		//visibility  不占位隐藏
		 }
	}
	
</script>
</html>
