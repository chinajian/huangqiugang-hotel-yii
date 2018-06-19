<?php
return [
    'adminEmail' => 'admin@example.com',
    'uploadPath' => '/uploads/',//后台上传文件保存路径
    'saveMode' => 'seesion',//系统信息保存方式，比如登录数据 seesion radis file 3中保存方式
    'pageSize' => 20,//每页显示20条
    'imgSize' => [
        'mini_img' => 70, //MINI图
        'thumb_img' => 300, //缩略图
        'big_img' => 750, //大图
        'large_img' => 1000 //巨图
    ],//上传的图片尺寸
];
