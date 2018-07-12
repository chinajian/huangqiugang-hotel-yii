<?php
$myfile = fopen("aa.txt", "w") or die("Unable to open file!");
$txt = '测试回调函数';
fwrite($myfile, $txt);
fclose($myfile);