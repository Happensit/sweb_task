<?php

namespace Task;
/**
 * @Data.php
 * Created by happensit for sweb.
 * Date: 01.02.16
 * Time: 22:34
 */

use SpaceWeb\Quest\QuestAbstract;


class Statistics extends QuestAbstract
{
    private $start_date, $end_date;

    /**
     * @param $start_date
     * @param $end_date
     */
    public function init($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $sql = "SELECT COUNT(p.id) as count, SUM(p.amount) as sum FROM payments as p
                WHERE p.create_ts BETWEEN ? AND ?";
        $query = $this->getDb()->prepare($sql);
        $query->execute([$this->start_date, $this->end_date]);

//        $result = $query->fetchColumn();


        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @return mixed
     */
    public function getWithDocs()
    {
        $sql = "SELECT COUNT(p.id) as count, SUM(p.amount) as sum FROM payments as p
                JOIN documents as d ON d.entity_id = p.id
                WHERE p.create_ts BETWEEN ? AND ?";

        $query = $this->getDb()->prepare($sql);
        $query->execute([$this->start_date, $this->end_date]);

        return $query->fetch(\PDO::FETCH_ASSOC);

    }

    /**
     * @return mixed
     */
    public function getWithoutDocs()
    {

        $sql = "SELECT COUNT(p.id) as count, SUM(p.amount) as sum FROM payments as p
                LEFT JOIN documents as d ON d.entity_id = p.id WHERE d.entity_id IS NULL AND
                p.create_ts BETWEEN ? AND ?";

        $query = $this->getDb()->prepare($sql);
        $query->execute([$this->start_date, $this->end_date]);

        return $query->fetch(\PDO::FETCH_ASSOC);

    }

}