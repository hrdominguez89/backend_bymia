<?php

namespace App\Command;

use App\Helpers\SendCustomerToCrm;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SendCustomerToCrmCommand extends Command
{
    protected static $defaultName = 'app:send-customer-to-crm';
    protected static $defaultDescription = 'This command send to crm the customer data';
    protected $sendCustomerToCrm;

    public function __construct(SendCustomerToCrm $sendCustomerToCrm)
    {
        parent::__construct(null);
        $this->sendCustomerToCrm = $sendCustomerToCrm;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->sendCustomerToCrm->SendCustomerPendingToCrm();

        $io->success('Se envio la info de los clientes al crm.');

        return Command::SUCCESS;
    }
}
