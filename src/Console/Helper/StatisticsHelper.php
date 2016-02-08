<?php
/**
 * @StatisticsHelper.php
 * Created by happensit for sweb.
 * Date: 05.02.16
 * Time: 1:38
 */

namespace Task\Console\Helper;

use Symfony\Component\Console\Helper\Helper;
use Doctrine\DBAL\Connection;

class StatisticsHelper extends Helper
{

    private $start_date, $end_date, $connection;

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
     * @param mixed $connection
     */
    public function setConnection($connection)
    {
        if (!$connection instanceof Connection) {
            throw new \InvalidArgumentException('The provided connection is not an instance of the Doctrine DBAL connection.');
        }

        $this->connection = $connection;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $sql = "SELECT COUNT(p.id) as count, SUM(p.amount) as sum FROM payments as p
                WHERE p.create_ts BETWEEN ? AND ?";
        return $this->connection->fetchAssoc($sql, [$this->start_date, $this->end_date], ['datetime','datetime']);

    }

    /**
     * @return mixed
     */
    public function getWithDocs()
    {
        $sql = "SELECT COUNT(p.id) as count, SUM(p.amount) as sum FROM payments as p
                JOIN documents as d ON d.entity_id = p.id
                WHERE p.create_ts BETWEEN ? AND ?";

        return $this->connection->fetchAssoc($sql, [$this->start_date, $this->end_date], ['datetime','datetime']);

    }

    /**
     * @return mixed
     */
    public function getWithoutDocs()
    {

        $sql = "SELECT COUNT(p.id) as count, SUM(p.amount) as sum FROM payments as p
                LEFT JOIN documents as d ON d.entity_id = p.id WHERE d.entity_id IS NULL AND
                p.create_ts BETWEEN ? AND ?";

        return $this->connection->fetchAssoc($sql, [$this->start_date, $this->end_date], ['datetime','datetime']);

    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
       return 'statistics';
    }

}