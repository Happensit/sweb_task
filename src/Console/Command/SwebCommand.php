<?php

namespace Task\Console\Command;

/**
 * @SwebCommand.php
 * Created by happensit for sweb.
 * Date: 01.02.16
 * Time: 22:08
 */

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Helper\Table;
use Task\Statistics;
use Task\Console\Helper\DateTime;
use Symfony\Component\Console\Exception\RuntimeException;

class SwebCommand extends Command
{

    protected function configure()
    {
        $this->setName("statistics")
          ->setDescription('Show statistics payments customers Sweb.ru')
          ->setHelp("Shows statistics payments customers for a given date. Use --with-documents and --without-documents params. Only in this order.")
          ->setDefinition(
            [
              new InputOption('with-documents', null, InputOption::VALUE_NONE, "Output data with documents"),
              new InputOption('without-documents', null, InputOption::VALUE_NONE, "Output data without documents"),
            ]
          );
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $helper = $this->getHelper('question');
        $question = new Question('Please enter start date: ', new DateTime('-6 month'));
        $question->setValidator(
          function ($answer) {
              try {
                  $answer = new DateTime($answer);
              } catch (\Exception $e) {
                  throw new RuntimeException(
                    "Please, input valid format date " . date('Y-m-d')
                  );
              }

              return $answer;
          }
        );

        $start_date = $helper->ask($input, $output, $question);


        $question = new Question(
          'Please enter end date: ', new DateTime('now')
        );
        $question->setValidator(
          function ($answer) {
              try {
                  $answer = new DateTime($answer);
                  $answer->setTime(23, 59, 59);
              } catch (\Exception $e) {
                  throw new RuntimeException(
                    "Please, input valid format date " . date('Y-m-d')
                  );
              }

              return $answer;
          }
        );

        $end_date = $helper->ask($input, $output, $question);

        if ($end_date > $start_date) {

            $data = new Statistics();
            $data->init($start_date, $end_date);

            $result[] = $data->getAll();

            if ($input->getOption('with-documents')) {
                $result[] = $data->getWithDocs();
            }

            if ($input->getOption('without-documents')) {
                $result[] = $data->getWithoutDocs();
            }

            $table = new Table($output);
            $table->setHeaders(['Count', 'Sum']);
            $rows = $result;
            $table->setRows($rows);
            $table->render();
        } else {
            throw new RuntimeException(
              "Something happens wrong.. The end date is less than the start date."
            );
        }
    }
}
