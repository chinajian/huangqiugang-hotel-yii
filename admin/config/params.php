<?php
return [
    'adminEmail' => 'admin@example.com',
    'uploadPath' => '/uploads/',//后台上传文件保存路径
    'saveMode' => 'seesion',//系统信息保存方式，比如登录数据 seesion radis file 3中保存方式
    'pageSize' => 50,//每页显示10条
    'imgSize' => [
        'mini_img' => 70, //MINI图
        'thumb_img' => 300, //缩略图
        'big_img' => 750 //大图
    ],//上传的图片尺寸
    // 'api' => array(
    // 	'login' => 'http://122.224.119.138:7312/ipmsgroup/router',//登录接口
    // 	'hotel' => 'http://122.224.119.138:7312/ipmsgroup/CRS/',//订房接口
    //  'user' => 'http://122.224.119.138:7311/ipmsmember/membercard/',//会员接口
    // ),
    'api' => array(
        'login' => 'http://123.206.217.123:8102/ipmsgroup/router',//登录接口
        'hotel' => 'http://123.206.217.123:8102/ipmsgroup/CRS/',//订房接口
    	'user' => 'http://123.206.217.123:8101/ipmsmember/membercard/',//会员接口
    ),
];
