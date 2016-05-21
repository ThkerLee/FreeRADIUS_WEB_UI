<?
header("Content-Type:text/html;charset=utf-8");
?>
<script>
//public

//online  51  74 96
var aj_dataLoad ="<? echo _("数据加载中...")?>";
//online  145  151 
var aj_IP ="<? echo _("请输入一个有效的IP地址")?>";
var aj_pj_userprefix="<? echo _("用户前缀匹配(@ 0-9 a-z _ -)")?>";
var  aj_pj_userstart ="<? echo _("用户开始ID必须为数字")?>"; 
var  aj_pj_userpwd ="<? echo _("用户密码匹配(@ 0-9 a-z _ -)")?>"; 
var  aj_pj_mtu_num ="<? echo _("安装费或MTU值或记账包必须为数字,记账包必须大于0")?>";
//project 

//online  261
var aj_pj_name_null ="<? echo _("项目名称不能为空")?>";
//online  269
var aj_pj_name_illegal ="<? echo _("用户名不合法")?>";
//online  275
var aj_pj_name_inf ="<? echo _("接口输入有误")?>";
//online  283
var aj_pj_name_pool_name ="<? echo _("请选择有效的地址池")?>";
//online  287
var aj_pj_name_days ="<? echo _("到期天数输入有误")?>";
//online  300
var aj_pj_name_pool_null ="<? echo _("地址池名称不能为空")?>";
//产品个数
var aj_user_period ="<? echo _("订单数为：")?>";

//product  

//online  309
var aj_pd_name_null ="<? echo _("产品名称不能为空")?>";
//online  313
var aj_pd_period_null ="<? echo _("产品计费周期不能为空")?>";
//online  317
var aj_pd_period_number ="<? echo _("产品计费周期必须为数字")?>";
var aj_pd_freeProduct ="<? echo _("没有建立免费产品的权限");?>";
var aj_pd_penalty_number="<? echo _("产品违约金必须为数字")?>";
//online  321
var aj_pd_price_null ="<? echo _("产品价格不能为空")?>";
//online  325
var aj_pd_price_number ="<? echo _("产品价格必须为数字")?>";
//online  329
var aj_pd_creditline_number ="<? echo _("信用额度必须为数字")?>";
//online  333
var aj_pd_upload_number ="<? echo _("上传速率必须为数字")?>";
//online  337
var aj_pd_download_number ="<? echo _("下载速率必须为数字")?>"; 
  
//user_add or user_edit
var aj_restore_refund_type ="<? echo _("退款方式")?>";
//online  350
var aj_user_parentsAccount_null ="<? echo _("您必须包含有效的母账号")?>";
//online  354
var aj_user_parentsAccount_exist ="<? echo _("您的填写的母账号不存在")?>";
//online  358
var aj_user_parentsAccount_sub ="<? echo _("请确认母账号不是其他账号的子账号")?>";
//online  365 674 690 716 
var aj_user_account_null ="<? echo _("您的帐号不能为空")?>";//
var aj_user_name_null ="<? echo _("您的真实姓名不能为空")?>";//
//var aj_user_limit_money  ="<? // echo _("最低预存金额为：")?>";
//online  369
//var aj_user_account_disabled ="</? echo _("您的帐号含违禁字符# 或 &")?>";
//online  378
var aj_user_account_exists ="<? echo _("您的帐号系统中已经存在")?>";
var aj_user_totalMoney ="<? echo _("收费金额已经达到上限,请联系管理员")?>"; 
var aj_user_monery_zero ="<? echo _("预存金额必须大于零")?>"
//online  383
var aj_user_pwd_null ="<? echo _("您的密码不能为空")?>";
var aj_user_pwd_numletters ="<? echo _("密码只能是数字或字母")?>";
//online  387
var aj_user_pwd_not_consistent ="<? echo _("您输入的两次密码不一致")?>";
//online  391 485
var aj_user_project_null ="<? echo _("您的项目不能为空")?>";
//online  396
var aj_user_phoneNum_null ="<? echo _("您的手机号码不能为空")?>";
//online  400
var aj_user_addr_null ="<? echo _("联系地址必填")?>";
//online  400 494 703 
var aj_user_product_select ="<? echo _("请您选择产品")?>";
//online  408 498 
var aj_user_installation_fees_number ="<? echo _("初装费必须为数字")?>";
//online  412 502  683 732  791
var aj_user_money_number ="<? echo _("金额必须为数字")?>";
//online  417
var aj_user_project_ros_IP ="<? echo _("所选项目启用ROSIP认证,请分配IP")?>";
//online  421
var aj_user_IP_null ="<? echo _("IP地址不能为空")?>";
//online  427
var aj_user_account_MAC ="<? echo _("用户名格式不正确,用户名格式为00:24:21:19:BD:E4即用户MAC地址")?>";
//online  444 508
var aj_user_installation_fees ="<? echo _("您所预存的金额不足支付所选择的产品的价格和初装费用,初装费用为:")?>";
//online  458
var aj_user_prefix_null ="<? echo _("输入的标识符不能为空")?>";
//online  462
var aj_user_start_ID_null ="<? echo _("开始ID不能为空")?>";
//online  466
var aj_user_start_ID_more_than1 ="<? echo _("开始ID必须为数字且大于等于1")?>";
//online  474
var aj_user_end_ID_null ="<? echo _("结束ID不能为空")?>";
//online  478
var aj_user_end_ID_more_than1 ="<? echo _("结束ID必须为数字且大于于等1")?>";
//online  478
var aj_user_start_end_ID_same ="<? echo _("开始ID与结束ID不可相同")?>";
//online  482
var aj_user_start_end_ID_little ="<? echo _("开始ID必须小于结束ID")?>";
//online  513
var aj_user_money_enough ="<? echo _("您所预存的金额不足支付所选择的产品的价格")?>";
//online  490
var aj_user_project_ros ="<? echo _("所选项目启用ROSIP认证,需分配IP,不允许批量添加")?>"; 

//用户充值
var aj_user_cardErr   ="<? echo _("充值卡号不存在")?>";
var aj_user_cardLost  ="<? echo _("充值卡号已经被充值")?>"; 
var aj_user_card_num ="<? echo _("充值卡不能为空")?>";
var aj_user_card_pwd ="<? echo _("充值卡密码不能为空")?>";
var aj_user_card_pwd_err ="<? echo _("密码不正确")?>";
var aj_user_financemoney ="<? echo _("缴费金额必须为数字");?>";
var aj_user_finmoney ="<? echo _("缴费金额");?>";

//管理员修改密码

//online  522
var aj_manager_old_pwd_error ="<? echo _("旧密码输入有误")?>";
//online  525
var aj_manager_old_new_pwd_match ="<? echo _("新密码两次输入不一致")?>"; 


//人工收费添加 finance_MTC
 
//online  532
var aj_finance_MTC_username_null ="<? echo _("用户名不能为空")?>"; 
//online  536
var aj_finance_MTC_username_exist ="<? echo _("输入的用户名不存在,请确认")?>";
//online  540
var aj_finance_MTC_type ="<? echo _("请选择支付类型")?>";
//online  544
var aj_finance_MTC_type_cash ="<? echo _("余额不足以支付,可选择现金支付")?>";
//online  544
var aj_finance_MTC_project_name ="<? echo _("请选择收费科目")?>";
//online  553
var aj_finance_MTC_money_number ="<? echo _("缴费金额必须为数字")?>"; 
//online  557
var aj_finance_MTC_money_more_than0 ="<? echo _("缴费金额必须大于0")?>"; 


 
//增加订单表单 order_add

//online  569  678
var aj_order_add_money_null ="<? echo _("金额不能为空填")?>"; 
//online  577
var aj_order_add_account_null ="<? echo _("输入的用户名不存在,请确认")?>"; 
var aj_user_orderCount = "<? echo _("该用户已有12个订单, 如需续费，请将该订单撤销后方可进行本次操作")?>";  
var aj_user_have_order="<? echo _("当前用户已有订单数:")?>" ;
var   aj_user_maxadd_order ="<? echo _("最多可续费订单为:")?>";
var aj_user_period_null  ="<? echo _("续费周期必填")?>";
//用户充值表单

//online  577
var aj_recharge_money_not0 ="<? echo _("您所充值的费用不可为0")?>"; 
 
//用户充帐表单  

//online  636
var aj_reverse_money_0 ="<? echo _("您要冲帐金额小于等于零没有实际意义")?>"; 
 
//用户帐号申请停机，恢复

//online  653
var aj_closing_type ="<? echo _("您的用户已经销户,不能对它进行操作")?>"; 
//online  666
var aj_closing_more_than ="<? echo _("请不要进行重复的操作")?>"; 

//用户更换产品 product_change

//online  699
var aj_product_change_order_add ="<? echo _("此帐号目前系统中不存正在使用的订单,需新添加一条新的产品订单")?>"; 
//online  707
var aj_product_change_money_notenough ="<? echo _("您所预存的金额不足支付所选择的产品的价格,您当前帐号余额加产品退返金额共:")?>"; 


//用户销户

//online  720
var aj_user_closing_type ="<? echo _("你把输入的用户已经销户,请不要重复操作")?>"; 
//online  727
var aj_user_closing_endtime ="<? echo _("您所操作的用户已经销户过了,请不要重复操作")?>"; 
//online  736 
var aj_closing_factmoney ="<? echo _("实退金额不能小于0")?>"; 
//online  740 
var aj_closing_order_status_0 ="<? echo _("此帐号目前存在一条未执行完的订单,是否销户")?>"; 
//online  746
var aj_closing_user_owe ="<? echo _("帐号欠的有费用你真的确定要销户")?>"; 
//online  750
var aj_closing_factmoney ="<? echo _("账号已欠费不能退费")?>"; 
//online  756
var aj_closing_factmoney_over ="<? echo _("实际退费金额不能超过应退金额")?>"; 
//卡片生成表单 card

//online  767
var aj_card_prefix_null ="<? echo _("卡片的前缀不能为空")?>"; 
//online  771
var aj_card_start_null ="<? echo _("卡片的起始编号不能为空")?>"; 
//online  775
var aj_card_start_number ="<? echo _("起始编号必须是0-9之间数字")?>"; 
//online  779
var aj_card_end_null ="<? echo _("卡片的结束编号不能为空")?>"; 
//online  783
var aj_card_end_null ="<? echo _("结束编号必须是0-9之间数字")?>"; 
//online  787
var aj_card_start_small_end ="<? echo _("卡片的结束编号不能小于起始编号")?>"; 
//online  787
var aj_card_money ="<? echo _("金额必须不能小于等于0")?>"; 

//故障添加 Repair


//online  787
var aj_repair_reason ="<? echo _("请认真填写事件原因")?>"; 
//online  853
var aj_dell ="<? echo _("您确定要删除吗？")?>"; 
//online  813
var aj_p_del ="<? echo _("请确认!")?>";  
//online  813
var aj_e_mail ="<? echo _("您的电子邮件不合法！")?>"; 
//online  833
var aj_en_name_illegal ="<? echo _("英文名不合法！")?>"; 
//online  838
var aj_ch_name_illegal ="<? echo _("中文名不合法！")?>"; 
//online  838
var aj_post_illegal ="<? echo _("邮政编码不合法！")?>"; 

var aj_user_totalNum="<? echo _("添加用户已达到上线,请联系管理员")?>";
//js/jsdate.js

//online  95
var aj_js_year = "<? echo _("年")?>"; 
var aj_js_moonth = "<? echo _("月")?>";  
//online 100
var aj_js_day = "<? echo _("今天")?>"; 
var aj_js_Sunday = "<? echo _("周日")?>"; 
var aj_js_Monday = "<? echo _("周一")?>"; 
var aj_js_Tuesday = "<? echo _("周二")?>"; 
var aj_js_Wednesday = "<? echo _("周三")?>"; 
var aj_js_Thursday = "<? echo _("周四")?>"; 
var aj_js_Friday = "<? echo _("周五")?>"; 
var aj_js_Saturday = "<? echo _("周六")?>";   
          
</script>