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
use Symfony\Component\PropertyAccess\PropertyAccessor;

#[AsCommand(
    name: 'app:load',
    description: 'Load a few products for testing',
)]
class LoadProductsCommand
{

    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager,
        ?string $name = null)
    {
    }


public function __invoke(
    SymfonyStyle $io,
): int
{
    // purge..
    foreach ($this->productRepository->findAll() as $product) {
        $this->entityManager->remove($product);
    }
    $this->entityManager->flush();
    foreach (['Rock', 'Paper', 'Scissors'] as $name) {
        if (!$product = $this->productRepository->findOneBy(['name' => $name])) {
            $product = (new Product());
            $this->entityManager->persist($product);
        }
        $product->setName($name);
//        if ($product->getName() !== $product->getId()) {}
    }
    $this->entityManager->flush();
    // hack for testing getters
//    $accessor = new PropertyAccessor();
//    foreach (['id','name'] as $property) {
////        $accessor->getValue($product, $property);
//    }

    $io->success(sprintf("%d products loaded", $this->entityManager->getRepository(Product::class)->count([])));

    return Command::SUCCESS;
}
}
