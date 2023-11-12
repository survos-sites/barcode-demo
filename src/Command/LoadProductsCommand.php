<?php

namespace App\Command;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-products',
    description: 'Load a few products for testing',
)]
class LoadProductsCommand extends Command
{

    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);
    }

protected
function configure(): void
{
    $this
        ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
}

protected
function execute(InputInterface $input, OutputInterface $output): int
{
    $io = new SymfonyStyle($input, $output);
    $arg1 = $input->getArgument('arg1');

    if ($arg1) {
        $io->note(sprintf('You passed an argument: %s', $arg1));
    }

    if ($input->getOption('option1')) {
        // ...
    }

    foreach (['Rock', 'Paper', 'Scissors'] as $name) {
        if (!$this->productRepository->findOneBy(['name' => $name])) {
            $product = (new Product())
                ->setName($name);
            $this->entityManager->persist($product);
        }

    }

    $this->entityManager->flush();

    $io->success(sprintf("%d products loaded", $this->entityManager->getRepository(Product::class)->count([])));

    return Command::SUCCESS;
}
}
