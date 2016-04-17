<?php

/**
 * @file        PasswordUpdate.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://xu42.cn/
 */


require_once 'Config.php';
require_once 'ToolCrawl.php';

/**
 * Class PasswordUpdate
 *
 * 密码修改
 *
 * 实例化需传递一个参数
 *      $cookie         登陆成功后的cookie值
 *
 * set 方法进行密码修改操作
 *      传递 2 个必须参数
 *      $old_passwd       旧密码
 *      $new_passwd       新密码
 */
class PasswordUpdate extends ToolCrawl
{
    public function __construct($cookie)
    {
        parent::__construct($cookie);
        $this->url = Config::$config['url_login'] . Config::$config['url_password_update'];
    }

    /**
     * 修改密码
     * @param $old_passwd       旧密码
     * @param $new_passwd       新密码
     */
    public function set ($old_passwd, $new_passwd)
    {
        $postdata = 'oldpassword=' . $old_passwd . '&password1=' . $new_passwd . '&password2=' .$new_passwd .'&upt=1';
        $res_data = $this->myCurl($this->url, $this->cookie, $postdata);
        return $res_data;
    }

}