<?php

return [

    'captcha'  => [
        // 验证码字符集合
        'codeSet'  => '1234567890', 
        // 验证码字体大小(px)
        'fontSize' => 15, 
        // 是否画混淆曲线
        'useCurve' => true, 
         // 验证码图片高度
        'imageH'   => 30,
        // 验证码图片宽度
        'imageW'   => 100, 
        // 验证码位数
        'length'   => 4, 
        // 验证成功后是否重置        
        'reset'    => true
    ],

];
