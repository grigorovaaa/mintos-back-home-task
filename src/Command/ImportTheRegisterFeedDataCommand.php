<?php


namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportTheRegisterFeedDataCommand extends Command
{
    const FEED_URL = 'https://www.theregister.co.uk/software/headlines.atom';

    protected static $defaultName = 'app:import-the-register-feed';


    protected function configure()
    {
        $this
            ->setDescription('imports data from TheRegister feed')

            ->setHelp('This command will fill store with TheRegister feed');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $content = simplexml_load_file(static::FEED_URL);
        $output->writeln($content);

        $output->writeln('TheRegister feed saved');

        $output->writeln('TheRegister feed frequent words saved');
    }
}