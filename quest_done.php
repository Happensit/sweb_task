<?php
/**
 * @quest_done.php
 * Created by happensit for sweb.
 * Date: 01.02.16
 * Time: 22:01
 */

if (!ini_get('date.timezone')) {
    date_default_timezone_set('Europe/Moscow');
}

require_once __DIR__.'/vendor/autoload.php';
use Symfony\Component\Console\Application;

$console = new Application('Sweb Task', '1.0.1');

$console->addCommands([
    new \Task\Console\Command\StatisticsCommand(),
    new \Task\Console\Command\GenerateCommand(),
]);

$dbConn = \Doctrine\DBAL\DriverManager::getConnection([
  'driver' => 'pdo_mysql',
  'dbname' => 'swebtask',
  'user' => 'root',
  'password' => 'root',
  'host' => '127.0.0.1',
  'charset' => 'utf8'
]);

$console->getHelperSet()->set(new \Task\Console\Helper\DatabaseHelper($dbConn));
$console->getHelperSet()->set(new \Task\Console\Helper\StatisticsHelper());

$console->run();
