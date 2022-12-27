<?php

namespace App\Command;

use App\Helpers\EnqueueEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SendQueuedEmailsCommand extends Command
{
    protected static $defaultName = 'app:send-queued-emails';
    protected static $defaultDescription = 'This command send the pending queued emails and up to ten emails wich error return';
    protected $queue;

    public function __construct(EnqueueEmail $queue)
    {
        parent::__construct(null);
        $this->queue = $queue;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->queue->sendEnqueue();

        $io->success('Los correos fueron enviados.');

        return Command::SUCCESS;
    }
}
