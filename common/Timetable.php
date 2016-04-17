<?php

/**
 * @file        Timetable.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://xu42.cn/
 */


require_once 'Config.php';
require_once 'ToolCrawl.php';

/**
 * Class Timetable
 *
 * 学期理论课表
 *
 * 实例化需传递一个参数
 *      $cookie     登陆成功后的cookie值
 *
 * 调用 get 方法
 *      传递 1 个必选参数, 1 个可选参数
 *      $semester   学期, 必选
 *      $weeks      周次, 可选 默认是本学期课表不分周次
 *
 */
class Timetable extends ToolCrawl
{

    public function __construct($cookie)
    {
        $this->cookie = $cookie;
        $this->url = Config::$config['url_login'] . Config::$config['url_timetable'];
    }

    /**
     * @param $semester     学年学期 格式例如：2015-2016-1
     * @param $weeks        周次 格式例如：1 (为空则获取本学期全部数据)
     */
    public function get($semester, $weeks = '')
    {
        $postdata = 'xnxq01id=' . $semester . '&zc=' . $weeks . '&sfFD=1&demo=&cj0701id=';
        $res_data = $this->myCurl($this->url, $this->cookie, $postdata);
        $data = $this->re($res_data);
        return $data;
    }

    /**
     * 解析出课表数据，三维数组，
     * 写的太恶心了，不注释了
     * @param $res_data 待解析的网页源代码
     */
    protected function re ($res_data)
    {
        preg_match_all('/<table(.*?)<\/table/s', $res_data, $theory_table);
        preg_match_all('/<tr>(.*?)<\/tr/s', $theory_table[1][1], $theory_tr);

        for($i = 0; $i < count($theory_tr[1]); $i++)
        {
            preg_match_all('/>(.*?)</',$theory_tr[1][$i],$temp);
            $theory_trr[] = $temp[1];
        }

        for($i = 0; $i < count($theory_trr); $i++)
        {
            $theory_empty[] = array_filter($theory_trr[$i]); // 删除数组空元素
        }

        for($i = 0; $i < count($theory_empty); $i++)
        {
            foreach($theory_empty[$i] as $v)
            {
                $theory[$i][] = $v;
            }
        }

        for($i = 1; $i < count($theory)-3; $i++)
        {
            $k = 0;
            for($j = 0; $j < count($theory[$i]);)
            {
                if($theory[$i][$j] != "&nbsp;" && $theory[$i][$j+3] != '----------------------')
                {
                    $table[$i-1][$k][0] = $theory[$i][$j];
                    $table[$i-1][$k][1] = $theory[$i][$j+1];
                    $table[$i-1][$k][2] = $theory[$i][$j+2];
                    $table[$i-1][$k][3] = $theory[$i][$j+4];
                    $j+=7;
                }elseif($theory[$i][$j+3] == '----------------------'){
                    $table[$i-1][$k][0] = $theory[$i][$j].'/'.$theory[$i][$j+4];
                    $table[$i-1][$k][1] = $theory[$i][$j+1].'/'.$theory[$i][$j+14];
                    $table[$i-1][$k][2] = $theory[$i][$j+2].'/'.$theory[$i][$j+15];
                    $table[$i-1][$k][3] = $theory[$i][$j+8].'/'.$theory[$i][$j+13];
                    $j+=16;
                }
                else{
                    $j+=2;
                }
                $k+=1;
            }
        }

        return $table;
    }
}