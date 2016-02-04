<?php
/**
 * @DatabaseHelper.php
 * Created by happensit for sweb.
 * Date: 04.02.16
 * Time: 21:45
 */

namespace Task\Console\Helper;

use Symfony\Component\Console\Helper\Helper;
use Doctrine\DBAL\Connection;

class DatabaseHelper extends Helper
{

    protected $connection;

    /**
     * DatabaseHelper constructor.
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
       return 'database';
    }
}