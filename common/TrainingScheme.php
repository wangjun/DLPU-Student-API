<?php
/**
 * @file        TrainingScheme.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://xu42.cn/
 */


require_once 'Config.php';
require_once 'ToolCrawl.php';

/**
 * Class TrainingScheme
 *
 * 指导培养方案
 *
 * 实例化需传递一个参数
 *      $cookie     登陆成功后的cookie值
 *
 * 调用 get 方法
 *      无需传递参数
 */
class TrainingScheme extends ToolCrawl
{
    public function __construct($cookie)
    {
        parent::__construct($cookie);
        $this->url = Config::$config['url_login'] . Config::$config['url_training_scheme'];
    }

    /**
     * 获取指导培养方案
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
     * @return array        指导培养方案
     */
    protected function re($res_data)
    {
        preg_match_all('/Nsb_r_list Nsb_table(.*?)table>/s', $res_data, $table);
        preg_match_all('/<tr>(.*?)<\/tr/s', $table[1][0], $tr);

        for ($i=0; $i < count($tr[1]); $i++) {
            preg_match_all('/">(.*?)<\//', $tr[1][$i], $td);
            $data[] = $td[1];
        }
        return $data;
    }

}