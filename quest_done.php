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

$console->addCommands(array(
  new Task\Console\Command\SwebCommand(),
));

$console->run();