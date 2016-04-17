<?php
/**
 * @file        ToolMongodb.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://xu42.cn/
 */

require_once "Config.php";

/**
 * Class ToolMongodb
 *
 * 工具 MongoDB
 * 封装 增删改查 操作
 */
class ToolMongodb
{

    protected $db_username          =       '';
    protected $db_password          =       '';
    protected $database_name        =       '';
    protected $collection_name      =       '';
    protected $namespace            =       '';
    protected $manger               =       NULL;
    protected $bluk                 =       NULL;
    protected $query                =       NULL;


    public function __construct($database_name, $collection_name)
    {
        $this->database_name        =       $database_name;
        $this->collection_name      =       $collection_name;
        $this->namespace            =       $this->database_name . '.' . $this->collection_name;
        $this->manger               =       new MongoDB\Driver\Manager('mongodb://' . Config::$config['mongodb_host'] . ':' . Config::$config['mongodb_port']);
        $this->bluk                 =       new MongoDB\Driver\BulkWrite();
    }


    /**
     * Add an insert operation to the bulk
     * @param $document     array|object, A document to insert.
     * @return mixed        If the document did not have an _id, a MongoDB\BSON\ObjectID will be generated and returned; otherwise, no value is returned.
     */
    public function insert ($document)
    {
        $this->bluk->insert($document);
        try{
            $bluk_write_result = $this->manger->executeBulkWrite($this->namespace, $this->bluk);
        }catch (MongoDB\Driver\Exception $e){
            $bluk_write_result = $e->getMessage();
        }
        return $bluk_write_result;
    }


    /**
     * Add an update operation to the bulk
     * @param $filter       array|object, The search filter.
     * @param $new_obj      array|object, A document containing either update operators (e.g. $set) or a replacement document (i.e. only field:value expressions).
     * @param $options      array, ['multi' => false, 'upsert' => false] is default
     * @return string       没有返回值
     */
    public function update ($filter, $new_obj, $options = [])
    {
        $this->bluk->update($filter, $new_obj, $options);
        try{
            $bluk_write_result = $this->manger->executeBulkWrite($this->namespace, $this->bluk);
        }catch (MongoDB\Driver\Exception $e){
            $bluk_write_result = $e->getMessage();
        }
        return $bluk_write_result;
    }


    /**
     * Add a delete operation to the bulk
     * @param $filter       array|object, The search filter.
     * @param $options      array, ['limit' => 0] is default, Delete all matching documents (limit=0), or only the first matching document (limit=1)
     * @return string       没有返回值
     */
    public function delete ($filter, $options = [])
    {
        $this->bluk->delete($filter, $options);
        try{
            $bluk_write_result = $this->manger->executeBulkWrite($this->namespace, $this->bluk);
        }catch (MongoDB\Driver\Exception $e){
            $bluk_write_result = $e->getMessage();
        }
        return $bluk_write_result;
    }


    /**
     * @param $filter       array|object, The search filter.
     * @param $options      array, http://docs.php.net/manual/zh/mongodb-driver-query.construct.php
     * @return array
     */
    public function find ($filter, $options = [])
    {
        $this->query = new MongoDB\Driver\Query($filter, $options);
        try{
            $query_result = $this->manger->executeQuery($this->namespace, $this->query);
        }catch (MongoDB\Driver\Exception $e){
            $query_result = $e->getMessage();
        }
        return $query_result;
    }

}