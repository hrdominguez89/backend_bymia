<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Helpers\SendSubcategoryTo3pl;

class SendSubcategoriesTo3plCommand extends Command
{
    protected static $defaultName = 'app:send-subcategories-to-3pl';
    protected static $defaultDescription = 'This command send to 3pl the subcategories data';
    protected $sendSubcategoryTo3pl;

    public function __construct(SendSubcategoryTo3pl $sendSubcategoryTo3pl)
    {
        parent::__construct(null);
        $this->sendSubcategoryTo3pl = $sendSubcategoryTo3pl;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->sendSubcategoryTo3pl->sendSubcategoryPendings();

        $io->success('Se enviaron las subcategorias al 3pl.');

        return Command::SUCCESS;
    }
}
