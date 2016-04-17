<?php
/**
 * @file        Handle.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://xu42.cn/
 */

require_once "../common/ToolDb.php";

/**
 * Class Handle
 *
 * 连接入口文件与各功能组件
 */
class Handle
{

    private $request            =       NULL;
    private $response           =       NULL;
    private $arguments          =       NULL;
    private $username           =       NULL;
    private $password           =       NULL;
    private $data_type          =       NULL;
    private $semester           =       NULL;
    private $exams_category     =       NULL;
    private $weeks              =       NULL;


    /**
     * Handle constructor.
     *
     * 初始化参数
     */
    public function __construct($request, $response, $arguments)
    {
        $this->request          =       $request;
        $this->response         =       $response;
        $this->arguments        =       $arguments;
        $this->username         =       $this->arguments['username'];
        $this->password         =       $this->request->getHeaderLine('X-DLPU-Password');
        $this->data_type        =       $this->request->getHeaderLine('X-DLPU-Data-Type');
        $this->semester         =       $this->request->getHeaderLine('X-DLPU-Semester');
        $this->exams_category   =       $this->request->getHeaderLine('X-DLPU-Exams-Category');
        $this->weeks            =       $this->request->getHeaderLine('X-DLPU-Weeks');
    }


    /**
     * 封装响应 参数缺少或格式错误
     */
    private function returnWrongParam()
    {
        $return_data = ['messages' => '参数缺少或格式错误', 'data' => ['']];
        $this->response->getBody()->write(json_encode($return_data));
        $response = $this->response->withStatus(422);
        $response = $response->withHeader('Content-type', 'application/json');
        return $response;
    }


    /**
     * 封装响应 账号或密码错误
     */
    private function returnWrongPassword()
    {
        $return_data = ['messages' => '账号或密码错误', 'data' => ['']];
        $this->response->getBody()->write(json_encode($return_data));
        $response = $this->response->withStatus(422);
        $response = $response->withHeader('Content-type', 'application/json');
        return $response;
    }


    /**
     * 封装响应 新密码格式错误
     */
    private function returnWrongNewPassword()
    {
        $return_data = ['messages' => '新密码长度应不小于8位, 且不可与旧密码或学号相同', 'data' => ['']];
        $this->response->getBody()->write(json_encode($return_data));
        $response = $this->response->withStatus(422);
        $response = $response->withHeader('Content-type', 'application/json');
        return $response;
    }


    /**
     * 封装响应 身份证号码错误
     */
    private function returnWrongIDCard()
    {
        $return_data = ['messages' => '身份证号码错误', 'data' => ['']];
        $this->response->getBody()->write(json_encode($return_data));
        $response = $this->response->withStatus(422);
        $response = $response->withHeader('Content-type', 'application/json');
        return $response;
    }


    /**
     * 封装响应 正确的结果
     * @param $data   数组
     */
    private function returnCorrectResult($data = '')
    {
        $return_data = ['messages' => 'success', 'data' => $data];
        $this->response->getBody()->write(json_encode($return_data));
        $response = $this->response->withStatus(200);
        $response = $response->withHeader('Content-type', 'application/json');
        return $response;
    }


    /**
     * 检查请求中的 username
     * 请求合法 return true
     * 请求非法 return false
     */
    private function checkUsername()
    {
        if(is_null($this->username) || strlen($this->username) != 10) return false;
        return true;
    }


    /**
     * 检查请求中的 password
     * 请求合法 return true
     * 请求非法 return false
     */
    private function checkPassword()
    {
        if(is_null($this->password) || strlen($this->password) < 6 ) return false;
        return true;
    }


    /**
     * 检查请求中的 semester
     * 请求合法 return true
     * 请求非法 return false
     */
    private function checkSemester()
    {
        if(is_null($this->semester) || strlen($this->semester) != 11) return false;
        return true;
    }


    /**
     * 模拟登陆获取cookie
     * @param $username     账号: 即学号
     * @param $password     密码：学号对应的密码
     * @return              登录成功则返回cookie信息登录失败则返回FALSE
     */
    private function getCookie($username, $password)
    {
        require_once "../common/ToolLogin.php";
        $cookie = (new ToolLogin($username, $password))->loginAndReturnCookieOrFalse();

        // 保存&更新 学号密码
        if($cookie)
            (new ToolDb(Config::$config['db_database'], Config::$config['db_collections_password']))->saveUsernameAndPassword($username, $password);
        return $cookie;
    }


    /**
     * 课程成绩
     */
    public function coursesScores()
    {
        if(!$this->checkUsername()) return $this->returnWrongParam();
        if(!$this->checkPassword()) return $this->returnWrongParam();

        $cookie = $this->getCookie($this->username, $this->password);
        if($cookie === false) return $this->returnWrongPassword();

        require_once "../common/CoursesScores.php";
        $data = (new CoursesScores($cookie))->get($this->semester);
        return $this->returnCorrectResult($data);
    }


    /**
     * 考试安排信息
     */
    public function examsInfo()
    {
        if(!$this->checkUsername()) return $this->returnWrongParam();
        if(!$this->checkPassword()) return $this->returnWrongParam();
        if(!$this->checkSemester()) return $this->returnWrongParam();

        $cookie = $this->getCookie($this->username, $this->password);
        if($cookie === false) return $this->returnWrongPassword();

        require_once "../common/ExamsInfo.php";
        $data = (new ExamsInfo($cookie))->get($this->semester, $this->exams_category);
        return $this->returnCorrectResult($data);
    }


    /**
     * 学期理论课程表
     */
    public function timetable()
    {
        if(!$this->checkUsername()) return $this->returnWrongParam();
        if(!$this->checkPassword()) return $this->returnWrongParam();
        if(!$this->checkSemester()) return $this->returnWrongParam();

        $cookie = $this->getCookie($this->username, $this->password);
        if($cookie === false) return $this->returnWrongPassword();

        require_once "../common/Timetable.php";
        $data = (new Timetable($cookie))->get($this->semester, $this->weeks);
        return $this->returnCorrectResult($data);
    }


    /**
     * 学籍卡片(个人信息)
     */
    public function userinfo()
    {
        if(!$this->checkUsername()) return $this->returnWrongParam();
        if(!$this->checkPassword()) return $this->returnWrongParam();

        $cookie = $this->getCookie($this->username, $this->password);
        if($cookie === false) return $this->returnWrongPassword();

        require_once "../common/Userinfo.php";
        $data = (new Userinfo($cookie))->get();
        (new ToolDb(Config::$config['db_database'], Config::$config['db_collections_userinfo']))->saveUserinfo($this->username, $data);
        return $this->returnCorrectResult($data);
    }


    /**
     * 教务处公告(登陆之后的首页公告)
     */
    public function notice()
    {
        if(!$this->checkUsername()) return $this->returnWrongParam();
        if(!$this->checkPassword()) return $this->returnWrongParam();

        $cookie = $this->getCookie($this->username, $this->password);
        if($cookie === false) return $this->returnWrongPassword();

        require_once "../common/Notice.php";
        $data = (new Notice($cookie))->get();
        return $this->returnCorrectResult($data);
    }


    /**
     * 密码修改
     */
    public function passwordUpdate()
    {
        if(!$this->checkUsername()) return $this->returnWrongParam();
        if(!$this->checkPassword()) return $this->returnWrongParam();

        // 新密码格式是否符合要求
        $allPostPutVars = $this->request->getParsedBody();
        $new_password = $allPostPutVars['password'];
        if(strlen($new_password) < 8) return $this->returnWrongNewPassword();
        if($new_password === $this->password) return $this->returnWrongNewPassword();
        if($new_password === $this->username) return $this->returnWrongNewPassword();

        $cookie = $this->getCookie($this->username, $this->password);
        if($cookie === false) return $this->returnWrongPassword();

        require_once "../common/PasswordUpdate.php";
        $data = (new PasswordUpdate($cookie))->set($this->password, $new_password);
        return $this->returnCorrectResult();
    }


    /**
     * 密码重置
     */
    public function passwordReset()
    {
        if(!$this->checkUsername()) return $this->returnWrongParam();

        // 身份证号是否正确
        $allPostPutVars = $this->request->getParsedBody();
        $id_card = $allPostPutVars['id'];
        if(strlen($id_card) != 18) return $this->returnWrongIDCard();

        require_once "../common/PasswordReset.php";
        $data = (new PasswordReset(''))->set($this->username, $id_card);
        if($data) return $this->returnCorrectResult('密码已重置为身份证号的后六位');
        return $this->returnWrongIDCard();
    }


    /**
     * 指导培养方案
     */
    public function trainingScheme()
    {
        if(!$this->checkUsername()) return $this->returnWrongParam();
        if(!$this->checkPassword()) return $this->returnWrongParam();

        $cookie = $this->getCookie($this->username, $this->password);
        if($cookie === false) return $this->returnWrongPassword();

        require_once "../common/TrainingScheme.php";
        $data = (new TrainingScheme($cookie))->get();
        return $this->returnCorrectResult($data);
    }


    /**
     * 等级考试成绩
     */
    public function LevelScores()
    {
        if(!$this->checkUsername()) return $this->returnWrongParam();
        if(!$this->checkPassword()) return $this->returnWrongParam();

        $cookie = $this->getCookie($this->username, $this->password);
        if($cookie === false) return $this->returnWrongPassword();

        require_once "../common/LevelScores.php";
        $data = (new LevelScores($cookie))->get();
        return $this->returnCorrectResult($data);
    }

}

