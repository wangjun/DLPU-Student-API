<?php

/**
 * @file        Notice.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://xu42.cn/
 */


require_once 'Config.php';
require_once 'ToolCrawl.php';

/**
 * Class Notice
 *
 * 教务处公告(登陆之后的首页公告)
 *
 * 实例化需传递一个参数
 *      $cookie     登陆成功后的cookie值
 *
 * 调用 get 方法
 *      无需传递参数
 */
class Notice extends ToolCrawl
{
    public function __construct($cookie)
    {
        $this->cookie = $cookie;
        $this->url = Config::$config['url_login'] . Config::$config['url_notice'];
    }

    /**
     * 获取公告信息
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
        preg_match_all('/<tr>(.|\n)*?<\/tr>/', $res_data, $announcement_tr);

        for($i=1; $i<=count($announcement_tr[0])-2; $i++) {
            preg_match_all('/">((.|\n)*?)<\/t/', $announcement_tr[0][$i+1], $list_temp);
            preg_match('/\'(.*?)\'/', $list_temp[1][4], $list_temp_a);
            $list_temp[1][4] = $this->url_host . $list_temp_a[1];
            $data[$i-1] = $list_temp[1];
        }
        return $data;
    }
}