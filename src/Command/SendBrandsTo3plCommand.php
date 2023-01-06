<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Helpers\SendBrandTo3pl;

class SendBrandsTo3plCommand extends Command
{
    protected static $defaultName = 'app:send-brands-to-3pl';
    protected static $defaultDescription = 'This command send to 3pl the brands data';
    protected $sendBrandTo3pl;

    public function __construct(SendBrandTo3pl $sendBrandTo3pl)
    {
        parent::__construct(null);
        $this->sendBrandTo3pl = $sendBrandTo3pl;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->sendBrandTo3pl->sendBrandPendings();

        $io->success('Se envio las marcas al 3pl.');

        return Command::SUCCESS;
    }
}
