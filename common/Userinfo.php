<?php

/**
 * @file        Userinfo.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://xu42.cn/
 */


require_once 'Config.php';
require_once 'ToolCrawl.php';

/**
 * Class Userinfo
 *
 * 学籍卡片(个人信息)
 */
class Userinfo extends ToolCrawl
{
    public function __construct($cookie)
    {
        $this->cookie = $cookie;
        $this->url = Config::$config['url_login'] . Config::$config['url_userinfo'];
    }

    /**
     * 获取详细的用户信息
     * @return array 用户信息
     */
    public function get()
    {
        $res_data = $this->myCurl($this->url, $this->cookie);
        $data = $this->re($res_data);
        return $data;
    }

    /**
     * @param $res_data 网页源代码
     * @return array 用户信息
     */
    protected function re($res_data)
    {
        preg_match_all('/>(.+?)<\/td>/', $res_data, $match_data_table);

        foreach($match_data_table[1] as $value){ //去除 数组元素中的 &nbsp;
            $match_data_table_trim[] = str_replace('&nbsp;', '', $value);
        }

        // 匹配出学号 $username[0]
        preg_match('/\d+/', $match_data_table_trim[6], $username);
        $match_data_table_trim[0] = $username[0];
        unset($match_data_table_trim[188]);
        return $match_data_table_trim;
    }

}