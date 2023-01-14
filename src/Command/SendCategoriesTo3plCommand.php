<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Helpers\SendCategoryTo3pl;

class SendCategoriesTo3plCommand extends Command
{
    protected static $defaultName = 'app:send-categories-to-3pl';
    protected static $defaultDescription = 'This command send to 3pl the categories data';
    protected $sendCategoryTo3pl;

    public function __construct(SendCategoryTo3pl $sendCategoryTo3pl)
    {
        parent::__construct(null);
        $this->sendCategoryTo3pl = $sendCategoryTo3pl;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->sendCategoryTo3pl->sendCategoryPendings();

        $io->success('Se envio las categorias al 3pl.');

        return Command::SUCCESS;
    }
}
