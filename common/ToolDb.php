<?php

/**
 * @file        ToolDb.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://xu42.cn/
 */

require_once "Config.php";
require_once "ToolMongodb.php";

/**
 * Class ToolDb
 *
 * 数据库操作类
 */
class ToolDb extends ToolMongodb
{

    public function __construct($database_name, $collection_name)
    {
        parent::__construct($database_name, $collection_name);
    }


    /**
     * 保存&更新 用户名和密码
     * @param $username         用户名(学号)
     * @param $password         密码
     */
    public function saveUsernameAndPassword ($username, $password)
    {
        $this->update(['_id' => $username], ['_id' => $username, 'password' => $password], ['multi' => false, 'upsert' => true]);
        return NULL;
    }


    /**
     * 查找 密码
     * @param $username         用户名(学号)
     * @return                  有则返回密码, 无则返回NULL
     */
    public function getPasswordByUsername ($username)
    {
        $filter = ['_id' => $username];
        $options = ['limit' => 1];

        $res = $this->find($filter, $options);
        $res_array = $res->toArray();
        if($res_array) return $res_array[0];
        return NULL;
    }


    /**
     * 保存&更新 个人信息
     * @param $username         用户名(学号)
     * @param $document         JSON格式
     * @return null
     */
    public function saveUserinfo ($username, $document)
    {
        $this->update(['_id' => $username], $document, ['multi' => false, 'upsert' => true]);
        return NULL;
    }


    /**
     * 查找 个人信息
     * @param $username         用户名(学号)
     * @return                  有则返回密码 无则返回NULL
     */
    public function getUserinfoByUsername ($username)
    {
        $filter = ['_id' => $username];
        $options = ['limit' => 1];

        $res = $this->find($filter, $options);
        $res_array = $res->toArray();
        if($res_array) return $res_array[0];
        return NULL;
    }


    /**
     * 保存&更新 成绩
     * @param $username         用户名(学号)
     * @param $semester         学年学期
     * @param $document         JSON格式
     * @return null
     */
    public function saveCoursesScores ($username, $semester, $document)
    {
        $this->update(['username' => $username, 'semester' => $semester], $document, ['multi' => false, 'upsert' => true]);
        return NULL;
    }


    /**
     * 查找 成绩
     * @param $username         用户名(学号)
     * @param $semester         学年学期
     * @return                  有则返回成绩 无则返回NULL
     */
    public function getCoursesScores ($username, $semester)
    {
        $filter = ['username' => $username, 'semester' => $semester];
        $options = ['limit' => 1];

        $res = $this->find($filter, $options);
        $res_array = $res->toArray();
        if($res_array) return $res_array[0];
        return FALSE;
    }


    /**
     * 保存&更新 学期理论课表
     * @param $username         用户名(学号)
     * @param $semester         学年学期
     * @param $weeks            周次
     * @param $document         JSON格式
     * @return null
     */
    public function saveTimetable ($username, $semester, $weeks, $document)
    {
        $this->update(['username' => $username, 'semester' => $semester, 'weeks' => $weeks], $document, ['multi' => false, 'upsert' => true]);
        return NULL;
    }


    /**
     * 查找 学期理论课表
     * @param $username         用户名(学号)
     * @param $semester         学年学期
     * @param $weeks            周次
     * @return                  有则返回课表 无则返回NULL
     */
    public function getTimetable ($username, $semester, $weeks)
    {
        $filter = ['username' => $username, 'semester' => $semester, 'weeks' => $weeks];
        $options = ['limit' => 1];

        $res = $this->find($filter, $options);
        $res_array = $res->toArray();
        if($res_array) return $res_array[0];
        return NULL;
    }


    /**
     * 保存&更新 考试安排信息
     * @param $username         用户名(学号)
     * @param $semester         学年学期
     * @param $category         类别
     * @param $document         JSON格式
     * @return null
     */
    public function saveExamsInfo ($username, $semester, $category, $document)
    {
        $this->update(['username' => $username, 'semester' => $semester, 'category' => $category], $document, ['multi' => false, 'upsert' => true]);
        return NULL;
    }


    /**
     * 查找 考试安排信息
     * @param $username         用户名(学号)
     * @param $semester         学年学期
     * @param $category         类别
     * @return                  有则返回考试安排 无则返回NULL
     */
    public function getExamsInfo ($username, $semester, $category)
    {
        $filter = ['username' => $username, 'semester' => $semester, 'category' => $category];
        $options = ['limit' => 1];

        $res = $this->find($filter, $options);
        $res_array = $res->toArray();
        if($res_array) return $res_array[0];
        return NULL;
    }

}