<?php

namespace Centcp;

/**
 * Query class for easy making mysql queries
 */

class QueryBuilder {
    protected $db;
    protected $sth;
    protected $blnIsExecuted = false;

    protected $arrSelect = array();
    protected $blnDistinct = false;
    protected $arrFrom = array();
    protected $arrWhere = array();
    protected $arrJoin = array();
    protected $arrOrder = array();
    protected $arrGroup = array();
    protected $arrHaving = array();
    protected $strLimit = '';

    protected $arrInsert = array();
    protected $arrOnDuplicate = array();

    protected $arrUpdate = array();
    protected $arrSet = array();
    
    protected $arrDelete = array();

    protected $arrValues = array();
    
    function __construct(&$db) {
        $this->db = &$db;
        
        return $this;
    }
    
    function distinct() {
        $this->blnDistinct = true;
        
        return $this;
    }
    
    function select($data) {
        $data = $this->toArray($data);
        foreach ($data as $item) {
            if (!in_array($item, $this->arrSelect)) {
                $this->arrSelect[] = $item;
            }
        }
        return $this;
    }

    function from($data, $as = '') {
        if(is_array($data)) {
            foreach ($data as $item) {
                if (!in_array($item, $this->arrFrom)) {
                    $this->arrFrom[] = '`' . $item . '`';
                }
            }
        }else {
            if (empty($as) && !in_array($data, $this->arrFrom)) {
                $this->arrFrom[] = '`' . $data . '`';
            }else {
                $this->arrFrom[] = '`' . $data . '` as ' . $as;
            }
        }
        return $this;
    }
    

    function where($key, $data = null, $equals = true) {
        
        if(is_array($key) && is_null($data)) {
            foreach($key as $field => $value) {
                $this->where($field, $value);
            }
            return $this;
        }
        
        $valueAlias = $this->valueAlias($key);
        
        //fix table prefix like: `table.field` => `table`.`field`
        if(strpos($key, '.') !== false && strpos($key, '`.`') === false) {
            $key = str_replace('.', '`.`', $key);
        }
        if (is_array($data)) {
            $values = array();
            foreach ($data as $i => $item) {
                $values[':' . $valueAlias . $i] = $item;
            }
            if ($equals === true) {
                $this->arrWhere[] = sprintf('`%s` IN (%s)', $key, join(', ', array_keys($values)));
            } else {
                $this->arrWhere[] = sprintf('`%s` NOT IN (%s)', $key, join(', ', array_keys($values)));
            }
            $this->value($values);

        } else {
            if ($equals === true) {
                $this->arrWhere[] = sprintf('`%s` = :%s', $key, $valueAlias);
            } elseif ($equals === false) {
                $this->arrWhere[] = sprintf('`%s`!= :%s', $key, $valueAlias);
            } elseif ($equals === '>') {
                $this->arrWhere[] = sprintf('`%s` > :%s', $key, $valueAlias);
            } elseif ($equals === '<') {
                $this->arrWhere[] = sprintf('`%s` < :%s', $key, $valueAlias);
            } elseif ($equals === '>=') {
                $this->arrWhere[] = sprintf('`%s` >= :%s', $key, $valueAlias);
            } elseif ($equals === '<=') {
                $this->arrWhere[] = sprintf('`%s` <= :%s', $key, $valueAlias);
            } else {
                /**
                 * @todo add other cases
                 **/
                $this->arrWhere[] = sprintf('`%s`!= :%s', $key, $valueAlias);
            }
            
            $this->value(array(':' . $valueAlias => $data));

        }
        return $this;
    }

    function whereSql($sql) {
        if (!in_array($sql, $this->arrWhere)) {
            $this->arrWhere[] = $sql;
        }
        return $this;
    }
    
    function join($table, $on, $joinType = '', $as = '') {
        $on = $this->toArray($on);
        $arrOn = array();
        foreach($on as $key => $value) {
            if(is_numeric($key)) {
                $arrOn[] = $value;
            }elseif(!is_array($value)) {
                $arrOn[] = $key . ' = :join_' . $table . '_' . $key;
                $this->value(array(':join_' . $table . '_' . $key => $value));
            }else {
                $values = array();
                foreach ($value as $i => $item) {
                    $values[':join_' . $table . '_' . $key . $i] = $item;
                }
                $arrOn[] = sprintf($key . ' IN (%s)', join(', ', array_keys($values)));
                $this->value($values);
            }
        }
        $joinType = strtoupper($joinType);
        if (!empty($joinType) && !in_array($joinType, array(
            'LEFT',
            'LEFT OUTER',
            'RIGHT',
            'RIGHT OUTER',
            'INNER',
            'CROSS'))) {
            $joinType = '';
        }
        if (!empty($table)) {
            $sqlJoin =  $joinType . ' JOIN ' . '`' . $table . '` ' . $as . (!empty($arrOn) ? ' ON (' . join(' AND ', $arrOn) . ' )' : '');
            if(!in_array($sqlJoin, $this->arrJoin)) {
                $this->arrJoin[] = $sqlJoin;
            }
        }
        
        return $this;
    }

    function order($data) {
        foreach ($data as $order => $way) {
            $this->arrOrder[] = $order . ' ' . $way;
        }
        
        return $this;
    }
    
    function group($data) {
        $data = $this->toArray($data);
        $this->arrGroup = array_merge($this->arrGroup, $data);
        
        return $this;                
    }
    
    function having($key, $data, $equals) {
        $valueAlias = $this->valueAlias($key);
        if ($equals) {
            $this->arrHaving[] = sprintf('`%s` = :%s', $key, $valueAlias);
        } else {
            $this->arrHaving[] = sprintf('`%s`!= :%s', $key, $valueAlias);
        }
        $this->value(array(':' . $valueAlias => $data));
        
        return $this;
    }

    function limit($limit, $page = 1) {
        $page = (intval($page) < 1) ? 1 : intval($page);
        $this->strLimit = sprintf('LIMIT %d, %d', ($limit * ($page - 1)), $page * $limit);
        
        return $this;
    }

    function insert($table, $data) {
        $data = $this->toArray($data);

        $this->arrInsert[$table] = array_keys($data);

        foreach ($data as $key => $value) {
            $this->arrValues[':' . $key] = $value;
        }

        return $this;
    }
    
    function onduplicate($data) {
        if(is_array($data)) {
            $this->arrOnDuplicate = array_merge($this->arrOnDuplicate, $data);
        }
        return $this;
    }

    function replace($table, $data) {
        $data = $this->toArray($data);

        $this->arrInsert[$table] = array_keys($data);

        foreach ($data as $key => $value) {
            $this->arrValues[':' . $key] = $value;
        }

        return $this;
    }

    function update($data) {
        $data = $this->toArray($data);
        foreach ($data as $item) {
            if (!in_array($item, $this->arrUpdate)) {
                $this->arrUpdate[] = '`' . $item . '`';
            }
        }
        return $this;
    }

    function set($key, $data = null) {
        if(is_array($key)) {
            foreach($key as $k => $v) {
                $valueAlias = $this->valueAlias($k);
                $this->arrSet[] = sprintf('`%s` = :%s', $k, $valueAlias);
                $this->value(array(':' . $valueAlias => $v));
            }
        }else {
            $valueAlias = $this->valueAlias($key);
            $this->arrSet[] = sprintf('`%s` = :%s', $key, $valueAlias);
            $this->value(array(':' . $valueAlias => $data));
        }
        
        return $this;
    }
    
    function setSql($key, $dataSql) {
        
        $this->arrSet[] = sprintf('`%s` = %s', $key, $dataSql);
        
        return $this;
    }
    
    function delete($data) {
        $data = $this->toArray($data);
        foreach ($data as $item) {
            if (!in_array($item, $this->arrDelete)) {
                $this->arrDelete[] = '`' . $item . '`';
            }
        }
        return $this;  
    }

    function value($data) {
        $data = $this->toArray($data);
        $this->arrValues = array_merge($this->arrValues, $data);
        
        return $this;
    }

    function valueAlias($key) {
        $newKey = str_replace('.', '', $key);
        if (key_exists(':' . $newKey, $this->arrValues)) {
            $newKey = false;
            $i = 0;
            while ($newKey === false) {
                if (!key_exists(':' . $key . '_' . ++$i, $this->arrValues)) {
                    $newKey = $key . '_' . $i;
                }
            }
        }
        return $newKey;
    }
    
    function setParams($params) {
        if(isset($params['limit'])) {
            $this->limit($params['limit'], (!empty($params['page']) ? $params['page'] : 1));
        }
        if(isset($params['order'])) {
            $this->order($params['order']);
        }
        
        return $this;
    }

    function getValues() {
        return $this->arrValues;
    }

    function buildSql($type) {
        if ($type == 'select') {
            $select = empty($this->arrSelect) ? array('*') : $this->arrSelect;
            $sql = sprintf("SELECT %s %s  \n %s %s \n %s \n %s %s \n %s %s \n %s %s \n %s %s \n %s ;", 
                          ($this->blnDistinct === true) ? 'DISTINCT' : '',
                          join(', ', $select), 
                          !empty($this->arrFrom) ? 'FROM' : '',
                          join(', ', $this->arrFrom), 
                          join("\n", $this->arrJoin), 
                          !empty($this->arrWhere) ? 'WHERE' : '', 
                          join(' AND ', $this->arrWhere), 
                          !empty($this->arrGroup) ? 'GROUP BY' : '',
                          join(', ', $this->arrGroup),
                          !empty($this->arrOrder) ? 'ORDER BY' : '', 
                          join(', ', $this->arrOrder),
                          !empty($this->arrHaving) ? 'HAVING' : '',
                          join(', ', $this->arrHaving),
                          $this->strLimit);
            return $sql;
        }
        if ($type == 'insert' && !empty($this->arrInsert)) {
            $fields = current($this->arrInsert);
            $sql = sprintf('INSERT INTO %s(%s) VALUES(%s)', join(', ', array_keys($this->arrInsert)), join(', ', $fields), join(', ', array_keys($this->arrValues)));
            if(!empty($this->arrOnDuplicate)) {
                $sql .= ' ON DUPLICATE KEY UPDATE ';
                $arrSqlSet = array();
                foreach($this->arrOnDuplicate as $key => $value) {
                    $arrSqlSet[] = $key . ' = ' . $value;
                }
                $sql .= join(', ', $arrSqlSet);
            }
            return $sql;
        }
        if ($type == 'replace' && !empty($this->arrInsert)) {
            $fields = current($this->arrInsert);
            $sql = sprintf('REPLACE INTO %s(%s) VALUES(%s) ;', join(', ', array_keys($this->arrInsert)), join(', ', $fields), join(', ', array_keys($this->arrValues)));
            return $sql;
        }
        if ($type == 'update' && !empty($this->arrUpdate)) {
            $sql = sprintf('UPDATE %s SET %s %s %s ;', join(', ', $this->arrUpdate), join(', ', $this->arrSet), !empty($this->arrWhere) ? 'WHERE' : '', join(' AND ', $this->arrWhere));
            return $sql;
        }
        if ($type == 'delete' && !empty($this->arrFrom)) {
            $sql = sprintf("DELETE %s \n FROM %s \n %s %s %s;", 
                            join(', ', $this->arrDelete),
                            join(', ', $this->arrFrom),
                            join("\n", $this->arrJoin), 
                            !empty($this->arrWhere) ? 'WHERE' : '', 
                            join(' AND ', $this->arrWhere));
            return $sql;
        }
        return false;
    }

    function prepare($type) {
        $this->sth = $this->db->prepare($this->buildSql($type));
        return $this;
    }
    
    function execute() {
        $this->blnIsExecuted = true;
        $this->sth = $this->db->execute($this->sth, $this->getValues());
        return $this;
    }
    
    function lastInsertId() {
        return $this->db->lastInsertId();
    }
    
    function fetchAll($key = null) {
        //check if query is executed
        if(!$this->blnIsExecuted) {
            $this->execute();
        }
                                
        return $this->db->fetchAll($this->sth, $key);
    }
    
    function fetch($type = \PDO::FETCH_ASSOC) {
        //check if query is executed
        if(!$this->blnIsExecuted) {
            $this->execute();
        }
        
        return $this->sth->fetch($type);
    }
    function affectedRows(){
        return $this->sth->rowCount();
    }
    function fetchOne() {
        $result = $this->fetch();
        if(!empty($result)) {
            return current($result);
        }
        
        return '';
    }

    /**
     * Convert data to array, prepare elements with type integer or string
     * @author igor
     * @param  mixed  $data
     * @param  string $type 
     * @return array 
     */
    function toArray($data, $type = '') {
        if (!is_array($data)) {
            if ($type == 'int') {
                $data = array((int)$data);
            } elseif ($type == 'string') {
                $data = array(sprintf('"%s"', ($data)));
            } else {
                $data = array($data);
            }
        } else {
            if ($type == "int") {
                foreach ($data as &$item) {
                    $item = (int)$item;
                }
            } elseif ($type == 'string') {
                foreach ($data as &$item) {
                    $item = sprintf('"%s"', ($item));
                }
            }
        }
        return $data;
    }

    function debug($strType, $blnPrint = true) {
        $strReturn = print_r($res = $this->buildSql($strType), $blnPrint ? false : true);
        $strReturn .= print_r($res = $this->getValues(), $blnPrint ? false : true);
        
        return $strReturn;
    }
}
