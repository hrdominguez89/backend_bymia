<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Helpers\SendProductTo3pl;

class SendProductsTo3plCommand extends Command
{
    protected static $defaultName = 'app:send-products-to-3pl';
    protected static $defaultDescription = 'This command send to 3pl the products data';
    protected $sendProductTo3pl;

    public function __construct(SendProductTo3pl $sendProductTo3pl)
    {
        parent::__construct(null);
        $this->sendProductTo3pl = $sendProductTo3pl;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->sendProductTo3pl->sendProductsPendings();

        $io->success('Se enviaron los productos al 3pl.');

        return Command::SUCCESS;
    }
}
