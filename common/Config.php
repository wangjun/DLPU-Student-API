<?php
/**
 * @file        Config.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://xu42.cn/
 */


/**
 * 配置文件
 *
 * 所有配置参数均在本文件进行定义
 */

class Config {
    public static $config = [
        'url_host'                          => 'http://210.30.62.8:8080',            // HOST
        'url_login'                         => 'http://210.30.62.8:8080/jsxsd/',     // 登陆页地址
        'url_courses_scores'                => 'kscj/cjcx_list',                     // 课程成绩页地址
        'url_exams_info'                    => 'xsks/xsksap_list',                   // 考试安排信息页地址
        'url_userinfo'                      => 'grxx/xsxx',                          // 学籍卡片页地址
        'url_level_scores'                  => 'kscj/djkscj_list',                   // 等级考试页地址
        'url_notice'                        => 'ggly/ysgg_query',                    // 首页公告页地址
        'url_password_reset'                => 'system/resetPasswd',                 // 密码重置页地址
        'url_password_update'               => 'grsz/grsz_xgmm',                     // 密码修改页地址
        'url_timetable'                     => 'xskb/xskb_list.do',                  // 学期理论课表页地址
        'url_training_scheme'               => 'pyfa/pyfazd_query',                  // 指导培养方案页地址
        'mongodb_host'                      => '127.0.0.1',                          // MongoDB 主机地址
        'mongodb_port'                      => '27017',                              // MongoDB 端口
        'db_database'                       => 'mydlpu',                             // 数据库名, 所有数据保存在此数据库
        'db_collections_password'           => 'password',                           // 集合名, 保存用户名和密码
        'db_collections_userinfo'           => 'userinfo',                           // 集合名, 保存用户信息
    ];


}
