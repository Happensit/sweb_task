<?php
/**
 * @GenerateCommand.php
 * Created by happensit for sweb.
 * Date: 04.02.16
 * Time: 14:57
 */

namespace Task\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GenerateCommand extends Command
{

    private $template = '<?php
namespace Task\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
/**
* <class>
*/
class <class> extends Command
{
    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName("<name>")
            ->setDescription("Command <name>")
            ->setHelp(<<<EOF
Help Command <name>
<info>php quest_done.php <name></info>
EOF
          );
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(array("","<info>Execute</info>",""));
    }
}';

    protected function configure()
    {
        $this
          ->setName('generate')
          ->setDescription('Generate skeleton class for new command')
          ->setHelp(<<<EOF
The <info>generate</info> command create skeleton of new command class.
<info>php quest_done.php generate</info>
EOF
          );
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(array(
          '',
          '<comment>Welcome to the command generator</comment>',
          ''
        ));

        $helper = $this->getHelper('question');

        $question = new Question("<info>Please enter the name of the command class</info>: ");

        $question->setValidator(
          function ($answer) {
              if ('Command' !== substr($answer, -7)) {
                  throw new \RunTimeException(
                    'The name of the command should be suffixed with \'Command\''
                  );
              }

              return $answer;
          }
        );

        $commandClass = $helper->ask($input, $output, $question);

        $commandName = $this->colonize($commandClass);
        $path = $this->generateCommand($commandClass, $commandName);
        $output->writeln(sprintf('Generated new command class to "<info>%s</info>"', realpath($path)));
    }

    protected function colonize($word)
    {
        $word = str_replace('Command', '', $word);
        return  strtolower(preg_replace('/[^A-Z^a-z^0-9]+/',':',
          preg_replace('/([a-zd])([A-Z])/','\1:\2',
            preg_replace('/([A-Z]+)([A-Z][a-z])/','\1:\2',$word))));
    }

    protected function generateCommand($commandClass, $commandName)
    {
        $placeHolders = array(
          '<class>',
          '<name>'
        );
        $replacements = array(
          $commandClass,
          $commandName
        );
        $code = str_replace($placeHolders, $replacements, $this->template);
        $path = __DIR__ . '/'.$commandClass.'.php';
        if (!file_exists(__DIR__)) {
            throw new \Exception(sprintf('Commands directory "%s" does not exist.', __DIR__));
        }
        file_put_contents($path, $code);
        return $path;
    }

}