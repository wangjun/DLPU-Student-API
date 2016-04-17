<?php

/**
 * @file        ExamsInfo.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://xu42.cn/
 */

require_once 'Config.php';
require_once 'ToolCrawl.php';


/**
 * Class Exams
 *
 * 考试安排信息
 *
 * 实例化需传递一个参数
 *      $cookie     登陆成功后的cookie值
 *
 * get 方法传递 1 个必选参数, 1 个可选参数
 *      $semester   学期, 必选
 *      $category   类别, 可选 默认类别是期末
 */
class ExamsInfo extends ToolCrawl
{
    public function __construct($cookie)
    {
        parent::__construct($cookie);
        $this->url = Config::$config['url_login'] . Config::$config['url_exams_info'];
    }

    /**
     * 获取考生考试安排信息
     * @param $semester     学年学期, eg. 2015-2016-1
     * @param $category     考试类别，1 => 期初, 2 => 期中, 3 => 期末
     * @return mixed        array 考试安排的数组列表
     */
    public function get ($semester, $category = '3')
    {
        $postdata = 'xnxqid=' . $semester . '&xqlb=' .$category;
        $res_data = $this->myCurl($this->url, $this->cookie, $postdata);
        $data = $this->re($res_data);
        return $data;
    }

    /**
     * @param $res_data     待解析的网页源码
     * @return mixed array  考试安排的数组列表
     */
    protected function re ($res_data)
    {
        preg_match_all('/<tr>(.*?)<\/tr>/s', $res_data,$temp_tr);

        for($i = 1; $i < count($temp_tr[1]); $i++)
        {
            preg_match_all('/>(.*?)<\/t/', $temp_tr[1][$i],$temp_td);
            $data[$i-1] = $temp_td[1];
            unset($data[$i-1][7]);
        }
        return $data;
    }

}