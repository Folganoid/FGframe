<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 20.04.17
 * Time: 11:30
 */

namespace Fg\Frame\Model;

use Fg\Frame\DI\DIInjector;
use Fg\Frame\Exceptions\BadQueryException;

/**
 * Class Model
 * @package Fg\Frame\Model
 */
class Model extends QueryBuilder
{

    protected $table = '';
    protected $db;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->db = DIInjector::get('database')->methods->connecting;
    }

    /**
     * Get all items
     *
     * @return mixed
     */
    public function getAll()
    {
        $this->setCase('select');
        $this->setOrderBy(['id DESC']);

        return $this->executeQuery(true);
    }

    /**
     * get One item
     *
     * @param int $id
     * @return mixed
     */
    public function getOne(int $id)
    {
        $this->setCase('select');
        $this->setWhere(['id =' . $id]);
        $this->setLimit(1);

        return $this->executeQuery(true);
    }


    /**
     * insert in DB
     *
     * @param array $values
     * @param array $columns
     */
    public function insert(array $values = [], array $columns = [])
    {
        $this->setCase('insert');
        $this->setColumns($columns);
        $this->setValues($values);

        $this->executeQuery();
    }

    /**
     * delete from DB
     *
     * @param $id
     */
    public function delete($id)
    {
        $this->setCase('delete');
        $this->setWhere(['id =' . $id]);

        $this->executeQuery();
    }

    /**
     * UPDATE method
     *
     * @param int $id
     * @param array $values
     * @param array $columns
     */
    public function update(int $id, array $values = [], array $columns = [])
    {
        $this->setCase('update');
        $this->setWhere(['id =' . $id]);
        $this->setColumns($columns);
        $this->setValues($values);

        $this->executeQuery();
    }

    /**
     * check DB query errors
     *
     * @param $stm
     * @throws BadQueryException
     */
    public function checkErrors($stm)
    {
        if ($stm->errorInfo()[0] != '00000') {
            throw new BadQueryException(implode(': ', $stm->errorInfo()));
        }
    }

    /**
     * execute query string
     *
     * @param bool $return
     * @param bool $isAll
     * @return mixed
     */
    public function executeQuery(bool $return = false, bool $isAll = false)
    {
        try {
            $stm = $this->db->prepare($this->build());
            $stm->execute();
            $this->checkErrors($stm);
        } catch (BadQueryException $e) {
            echo $e->getMessage();
        }
        if ($return) {
            if ($isAll) {
                return $stm->fetchAll(\PDO::FETCH_ASSOC);
            }
            return $stm->fetch(\PDO::FETCH_ASSOC);
        }
    }

}