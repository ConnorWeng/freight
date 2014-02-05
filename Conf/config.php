<?php
return array(
    // 数据库配置
    'DB_TYPE' => 'Oracle', // 数据库类型
    //'DB_HOST' => '192.168.199.205', // 服务器地址
    //'DB_NAME' => '192.168.199.205:1521/xe', // 数据库名
    'DB_HOST' => '10.211.55.3', // 服务器地址
    'DB_NAME' => '10.211.55.3:1521/xe', // 数据库名
    'DB_USER' => 'BANK', // 用户名
    'DB_PWD' => 'BANK', // 密码
    'DB_PORT' => '1521', // 端口
    'DB_PREFIX' => 'FREIGHT_',

    // 字符串常量
    'TODO_SR_NAME' => '受让清单需审核',
    'TODO_HX_NAME' => '核销清单需审核',
    'BANK_USER_ROLE' => '1',
);
?>