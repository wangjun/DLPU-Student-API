<?php
/**
 * @file        LevelScores.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://xu42.cn/
 */


require_once 'Config.php';
require_once 'ToolCrawl.php';

/**
 * Class LevelScores
 *
 * 等级考试成绩
 *
 * 实例化需传递一个参数
 *      $cookie     登陆成功后的cookie值
 *
 * 调用 get 方法
 *      无需传递参数
 */
class LevelScores extends ToolCrawl
{
    public function __construct($cookie)
    {
        parent::__construct($cookie);
        $this->url = Config::$config['url_login'] . Config::$config['url_level_scores'];
    }

    /**
     * 获取等级考试成绩
     * @return array
     */
    public function get()
    {
        $res_data = $this->myCurl($this->url, $this->cookie);
        $data = $this->re($res_data);
        return $data;
    }

    /**
     * @param $res_data     网页源代码
     * @return array        等级考试成绩
     */
    protected function re($res_data)
    {
        preg_match_all('/Nsb_r_list Nsb_table(.*?)table>/s', $res_data, $table);
        preg_match_all('/<tr>(.*?)<\/tr/s', $table[1][0], $tr);

        for ($i=0; $i < count($tr[1]); $i++) {
            preg_match_all('/>(.*?)<\/t/', $tr[1][$i], $td);
            $data[] = $td[1];
        }

        $data[1] = [
            $data[0][0],
            $data[0][1],
            $data[0][2].$data[1][0],
            $data[0][2].$data[1][1],
            $data[0][2].$data[1][2],
            $data[0][3].$data[1][0],
            $data[0][3].$data[1][1],
            $data[0][3].$data[1][2],
            $data[0][4]
        ];

        unset($data[0]);
        foreach($data as $value) $res[] = $value;
        return $res;
    }
}