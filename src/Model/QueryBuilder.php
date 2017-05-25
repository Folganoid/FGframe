<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 20.04.17
 * Time: 11:55
 */

namespace Fg\Frame\Model;

/**
 * Class QueryBuilder
 * @package Fg\Frame\Model
 */
class QueryBuilder
{
    /**
     * @var string - DB method
     */
    protected $case = 'select';

    /**
     * @var array - columns
     */
    protected $columns = [];

    /**
     * @var array - VALUES
     */
    protected $values = [];

    /**
     * @var array - WHERE conditions
     */
    protected $where = [];

    /**
     * @var string - TABLEname
     */
    protected $table = '';

    /**
     * @var array - set ORDER BY columns (for reverse sorting: 'column DESC')
     */
    protected $orderBy = [];

    /**
     * @var array - set LEFT JOIN ON
     */
    protected $leftJoin = [];

    /**
     * @var int - LIMIT;
     */
    protected $limit = 0;

    /**
     * @var int - OFFSET
     */
    protected $offSet = 0;

    /**
     * @var string - RETURNING
     */
    protected $returning = '';

    /**
     * set DB method
     * @param string $case
     */
    public function setCase(string $case = 'select')
    {
        $this->case = $case;
    }

    /**
     * set columns
     * @param array $columns
     */
    public function setColumns(array $columns = [])
    {
        $this->columns = $columns;
    }

    /**
     * set VALUES
     * @param array $values
     */
    public function setValues(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * set WHERE conditions
     * @param array $where
     */
    public function setWhere(array $where = [])
    {
        $this->where = $where;
    }

    /**
     * set TABLEname
     * @param string $table
     */
    public function setTable(string $table = '')
    {
        $this->table = $table;
    }

    /**
     * set ORDER BY columns
     * @param array $order
     */
    public function setOrderBy(array $order = [])
    {
        $this->orderBy = $order;
    }

    public function setLeftJoin(array $leftJoin)
    {
        $this->leftJoin = $leftJoin;
    }

    /**
     * set LIMIT diapason
     * @param int $limit
     * @param int $offset
     */
    public function setLimit(int $limit = 0, int $offset = 0)
    {
        $this->limit = $limit;
        $this->offSet = $offset;
    }

    public function setReturning(string $col)
    {
        $this->returning = $col;
    }

    /**
     * build DBSQL query string
     * @return string
     */
    public function build(): string
    {
        switch ($this->case) {
            case 'delete':
                $sql = 'DELETE FROM ' . $this->table .
                    $this->buildWhere() .
                    $this->buildReturning();
                break;

            case 'update':
                $sql = 'UPDATE ' . $this->table . ' SET ' .
                    $this->buildSet() .
                    $this->buildWhere() .
                    $this->buildReturning() . ';';
                break;

            case 'insert':
                $sql = 'INSERT INTO ' . $this->table .
                    ((count($this->columns) > 0) ? ' (' . $this->buildColumns() . ') ' : '') .
                    ' VALUES (' . $this->buildValues() . ') ' . $this->buildReturning(). ';';
                break;

            case 'select':
            default:
                $sql = 'SELECT ' . $this->buildColumns() .
                    ' FROM ' . $this->table .
                    $this->buildLeftJoin() .
                    $this->buildWhere() .
                    $this->buildOrder() .
                    $this->buildLimit();
                break;
        }
        return $sql;
    }

    /**
     * build columns string
     * @return string
     */
    protected function buildColumns(): string
    {
        if (count($this->columns) > 0) {
            return implode(', ', $this->columns);
        } else {
            return '*';
        }
    }

    /**
     * build VALUES string
     * @return string
     */
    protected function buildValues(): string
    {
        if (count($this->values) > 0) {
            return implode(', ', $this->values);
        } else {
            return '';
        }
    }

    /**
     * build WHERE conditions string
     * @return string
     */
    protected function buildWhere(): string
    {
        if (count($this->where) > 0) {
            return ' WHERE ' . implode(' AND ', $this->where);
        } else {
            return '';
        }

    }

    /**
     * build ORDER BY string
     * @return string
     */
    protected function buildOrder(): string
    {
        if (count($this->orderBy) > 0) {
            return ' ORDER BY ' . implode(', ', $this->orderBy);
        } else {
            return '';
        }
    }

    /**
     * build LIMIT diapason string
     * @return string
     */
    protected function buildLimit(): string
    {
        if ($this->limit) {
            if ($this->offSet) {
                return ' LIMIT ' . $this->offSet . ', ' . $this->limit . ';';
            }
            return ' LIMIT ' . $this->limit . ';';
        }

        return ';';
    }

    /**
     * build SET params string for INSERT INTO
     * @return string
     */
    protected function buildSet(): string
    {
        $sql = '';
        if (count($this->values) == count($this->columns)) {
            for ($i = 0; $i < count($this->values); $i++) {
                $sql .= ($this->columns[$i] . ' = ' . $this->values[$i] . ', ');
            }
            $sql = substr($sql, 0, -2);
        };
        return $sql;

    }

    /**
     * build LEFT JOIN ON string
     *
     * @return string
     */
    protected function buildLeftJoin()
    {
        $sql = '';
        if (count($this->leftJoin) > 0) {
            for ($i = 0; $i < count($this->leftJoin); $i++) {
                $sql .= ' LEFT OUTER JOIN ' . $this->leftJoin[$i][0] . ' ON (' . $this->leftJoin[$i][1] . ') ';
            }
        }
        $this->leftJoin = []; //reset
        return $sql;
    }

    protected function buildReturning()
    {
        $sql = '';
        if(strlen($this->returning) > 0) {
            $sql = ' RETURNING ' . $this->returning;
        }
        $this->returning = ''; //reset
        return $sql;
    }

}