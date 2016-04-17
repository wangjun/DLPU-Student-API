<?php
/**
 * @file        ToolCrawl.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://xu42.cn/
 */



/**
 * Class ToolCrawl
 *
 * 工具 封装网页抓取
 *
 */
class ToolCrawl
{

    protected $cookie       = NULL;     // 保存Cookie信息
    protected $url          = NULL;     // 当前的网络请求页

    public function __construct($cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * 对外暴露的唯一方法
     * 获取资源
     * @return mixed 格式化后(正则解析)的数据
     */
    public function get()
    {
        $res_data = $this->myCurl($this->url, $this->cookie);
        $data = $this->re($res_data);
        return $data;
    }

    /**
     * 一个简单的封装CURL网络请求的函数
     * @param $url      请求地址
     * @param $cookie   发送的Cookie
     * @return mixed    服务器响应 网页源代码
     */
    protected function myCurl($url, $cookie, $postdata = '')
    {
        $headers = array('Content-Length:'.strlen($postdata), 'Referer:'.$this->url_login, 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res_data = curl_exec($ch);
        return $res_data;
    }

    /**
     * 正则解析网页
     * @param $res_data
     * @return mixed
     */
    protected function re ($res_data) {
        return $res_data;
    }
}
