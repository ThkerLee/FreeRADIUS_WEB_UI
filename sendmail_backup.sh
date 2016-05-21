#!/bin/sh

PATH=/sbin:/usr/sbin:/bin:/usr/bin:/usr/local/sbin/:/usr/local/bin
export PATH 

from=$1
to=$2
file=$3
server=$4
pwd=$5

cd sendmailbackup

cat <<EOF>t.txt
From: $from 
Sender: $from 
To: $to 
Subject: SQL Backup 



EOF
 


uuencode $file $file>attachment

cat t.txt attachment>message

sendmail -t -f $from  -S $server -au$from -ap$pwd < message 

rm -f message t.txt  attachment


cd -