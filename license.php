#!/bin/php
<?php
        $file = popen("license -T","r");
        $data = fgets($file);
        pclose($file);
echo $data;