<?php
/**
 * @file        ToolLogin.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://xu42.cn/
 */


require_once 'Config.php';

/**
 * Class ToolLogin
 *
 * 工具 用户登陆教务处
 *
 * 实例化需传递两个参数
 *      $username    账号: 即学号
 *      $password    密码：学号对应的密码
 */
class ToolLogin
{
    /**
     * @var $username string 学生登陆名(这里是学号)
     * @var $password string 学生登陆密码
     */
    private $username = '';
    private $password = '';

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * 判断是否登录成功 并返回Cookie或者False
     * @param $username string      学生登陆名(这里是学号)
     * @param $password string      学生登陆密码
     * @return                      登录成功则返回cookie信息 登录失败则返回FALSE
     */
    public function loginAndReturnCookieOrFalse()
    {
        $post_data = "USERNAME=$this->username&PASSWORD=$this->password";
        $res_data = $this->login(Config::$config['url_login'], $post_data);
        preg_match('/Location:\s(.*?)\sContent/', $res_data, $matches);
        (count($matches) == 2) ? ($isSuccess = TRUE) : ($isSuccess = FALSE);
        if($isSuccess){
            return $this->reCookie($res_data);
        }else{
            return FALSE;
        }
    }

    /**
     * 获取登录成功后的重定向的页面地址
     * 只有登录成功才会有值，否则为NULL
     * @param $username         学生学号
     * @param $password         登录密码
     * @return                  string URL value OR NULL
     */
    public function getLocation()
    {
        $post_data = "USERNAME=$this->username&PASSWORD=$this->password";
        $res_data = $this->login($this->url_login, $post_data);
        $location = $this->reLocation($res_data);
        return $location;
    }

    /**
     * 登录 返回网页源代码
     * @param $url              系统登录页面地址
     * @param $post_data        向验证学号与密码的页面POST数据
     * @return                  mixed 服务器响应 网页源代码
     */
    private function login($url, $post_data)
    {
        $url = $url . 'xk/LoginToXk';
        $res = $this->myLoginCurl($url, $post_data);
        return $res;
    }

    /**
     * 一个简单的封装CURL网络请求的函数
     * @param $url string           请求地址
     * @param $post_data string     发送的数据
     * @return mixed 服务器response  网页源代码
     */
    protected function myLoginCurl($url, $post_data)
    {
        $headers = array('Content-Length:'.strlen($post_data), 'Referer:'.$this->url_login, 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
        $res_data = curl_exec($ch);
        return $res_data;
    }


    /**
     * 从网页源代码中解析出Cookie信息
     * @param $res_data string      网页源码
     * @return string               Cookie Value
     */
    private function reCookie($res_data)
    {
        preg_match('/Set-Cookie:\s(.*?);/', $res_data, $cookie);
        return trim($cookie[1]);
    }


    /**
     * 从网页源代码中解析出重定向的地址信息
     * 只有登录成功才会有值，否则为NULL
     * @param $res_data string      网页源码
     * @return string URL value OR NULL
     */
    private function reLocation($res_data)
    {
        preg_match('/Location:\s(.*?)\sContent/', $res_data, $location);
        return trim($location[1]);
    }

}
