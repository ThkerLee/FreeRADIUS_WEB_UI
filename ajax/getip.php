#!/bin/php
<?php
include_once("../inc/conn.php");
function ipD2H($strIP){
        return dechex($strIP[0]*16777216+$strIP[1]*65536+$strIP[2]*256+$strIP[3]);
}
function ipcount($bip,$eip){
        return
($eip[0]*16777216+$eip[1]*65536+$eip[2]*256+$eip[3])-($bip[0]*16777216+$bip[1]*65536+$bip[2]*256+$bip[3]);
}
function ipH2D($hip){
        if(strlen($hip)==7){
                $hip="0".$hip;
        }
        if(strlen($hip)==8){
                $ipsection=array();
                $j=0;
                for($i=0;$i<4;$i++){
                        $ipsection[$i]=hexdec(substr($hip,$j,2));
                        $j=$j+2;
                }
                        return  implode(".",$ipsection);
        }//end if
        else{

                $input_errors[] =  "非法的十六进制IP地址!";
        }//end if else
}//end function
if($_GET['projectID']){ 
	 $sql =$db->select_one("beginip,endip","project","ID='".$_GET['projectID']."'"); 
	if($sql){ 
		 $start_ip=$sql["beginip"];
		 $end_ip  =$sql["endip"];
	   $bips=explode(".",$start_ip);
	   $eips=explode(".",$end_ip);
	   $icount=ipcount($bips,$eips)+1;  
	   for($i=0;$i<$icount;$i++){
			$currentlyIP = ipH2D(dechex($bips[0]*16777216+$bips[1]*65536+$bips[2]*256+$bips[3]+$i)); 
			 $ip =$db->select_one("Value","radreply","Value='$currentlyIP' And Attribute='Framed-IP-Address';");
	    if(!$ip)  break; 
		}
   echo  trim($currentlyIP) ;
	}
}


?>