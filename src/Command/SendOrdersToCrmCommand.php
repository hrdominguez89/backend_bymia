<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Helpers\SendOrderToCrm;

class SendOrdersToCrmCommand extends Command
{
    protected static $defaultName = 'app:send-orders-to-crm';
    protected static $defaultDescription = 'This command send to crm the orders data';
    protected $sendOrderToCrm;

    public function __construct(SendOrderToCrm $sendOrderToCrm)
    {
        parent::__construct(null);
        $this->sendOrderToCrm = $sendOrderToCrm;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->sendOrderToCrm->SendOrderPendingToCrm();

        $io->success('Se enviaron las ordenes pendientes al crm.');

        return Command::SUCCESS;
    }
}
