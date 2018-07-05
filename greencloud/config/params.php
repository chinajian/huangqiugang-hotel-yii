<?php
return [
    'adminEmail' => 'admin@example.com',
    'saveMode' => 'file',//系统信息保存方式，比如登录数据 seesion radis file 3中保存方式
    'api' => array(
    	'login' => 'http://122.224.119.138:7312/ipmsgroup/router',//登录接口
    	'hotel' => 'http://122.224.119.138:7312/ipmsgroup/CRS/',//订房接口
    	'user' => 'http://122.224.119.138:7311/ipmsmember/membercard/',//会员接口
    ),
];
