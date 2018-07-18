<?php
return [
    'adminEmail' => 'admin@example.com',
    'pageSize' => 10,//每页显示10条
    // 'api' => array(
    //  'login' => 'http://122.224.119.138:7312/ipmsgroup/router',//登录接口
    //  'hotel' => 'http://122.224.119.138:7312/ipmsgroup/CRS/',//订房接口
    //  'user' => 'http://122.224.119.138:7311/ipmsmember/membercard/',//会员接口
    // ),
    'api' => array(
        'login' => 'http://123.206.217.123:8102/ipmsgroup/router',//登录接口
        'hotel' => 'http://123.206.217.123:8102/ipmsgroup/CRS/',//订房接口
        'user' => 'http://123.206.217.123:8101/ipmsmember/membercard/',//会员接口
    ),
    'imgSize' => [
        'mini_img' => 70, //MINI图
        'thumb_img' => 300, //缩略图
        'big_img' => 750 //大图
    ],//上传的图片尺寸
];
