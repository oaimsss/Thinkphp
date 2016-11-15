<?php

/**
 * @link http://blog.kunx.org/.
 * @copyright Copyright (c) 2016-11-8 
 * @license kunx-edu@qq.com.
 */

namespace Admin\Logic;

/**
 * Description of MySQLORM
 *
 * @author kunx <kunx-edu@qq.com>
 */
class MySQLORM implements Orm {

    public function connect() {
        echo '<pre>';
        echo __METHOD__ . '<br />';
        var_dump(func_get_args());
        echo '<hr />';
    }

    public function disconnect() {
        echo '<pre>';
        echo __METHOD__ . '<br />';
        var_dump(func_get_args());
        echo '<hr />';
    }

    public function free($result) {
        echo '<pre>';
        echo __METHOD__ . '<br />';
        var_dump(func_get_args());
        echo '<hr />';
    }

    public function getAll($sql, array $args = array()) {
        echo '<pre>';
        echo __METHOD__ . '<br />';
        var_dump(func_get_args());
        echo '<hr />';
    }

    public function getAssoc($sql, array $args = array()) {
        echo '<pre>';
        echo __METHOD__ . '<br />';
        var_dump(func_get_args());
        echo '<hr />';
    }

    public function getCol($sql, array $args = array()) {
        echo '<pre>';
        echo __METHOD__ . '<br />';
        var_dump(func_get_args());
        echo '<hr />';
    }

    public function getOne($sql, array $args = array()) {
        $args = func_get_args();
        $sql = $this->_buildSql($args);
        $rows = M()->query($sql);//结果集二维数组
        $row = array_pop($rows);//第一行
        $field = array_pop($row);//第一个字段
        return $field;
    }

    /**
     * 获取一行记录。
     * @param type $sql
     * @param array $args 
     * @return array 一行记录的关联数组。
     */
    public function getRow($sql, array $args = array()) {
        $args = func_get_args();
        $sql = $this->_buildSql($args);
        return array_pop(M()->query($sql));
    }

    /**
     * 执行插入。
     * @param type $sql
     * @param array $args
     * @return type
     */
    public function insert($sql, array $args = array()) {
        $table = func_get_arg(1);
        $data = func_get_arg(2);
        return M()->table($table)->add($data);
    }

    /**
     * 执行写操作
     * @param type $sql
     * @param array $args
     * @return type
     */
    public function query($sql, array $args = array()) {
        $args = func_get_args();
        $sql = $this->_buildSql($args);
        return M()->execute($sql);
    }

    public function update($sql, array $args = array()) {
        echo '<pre>';
        echo __METHOD__ . '<br />';
        var_dump(func_get_args());
        echo '<hr />';
    }

    /**
     * 拼凑sql语句。
     * @param array $args
     * @return string
     */
    private function _buildSql(array $args){
        $sql  = array_shift($args); //获取sql结构
        $sqls = preg_split('/\?[FTN]/', $sql); //将sql结构转换成数组
        array_pop($sqls); //弹出最后的空元素
        $sql  = '';
        foreach ($sqls as $key => $value) {
            $sql .=$value . $args[$key];
        }
        return $sql;
    }
}
