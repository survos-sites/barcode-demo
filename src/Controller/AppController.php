<?php

namespace App\Controller;

use App\Kernel;
use App\Repository\ProductRepository;
use Survos\BarcodeBundle\Service\BarcodeService;
use Survos\KeyValueBundle\Entity\KeyValueManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Kernel as KernelAlias;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{

    public function __construct(
        private BarcodeService $barcodeService)
    {
    }

    #[Route('/', name: 'app_homepage', methods: [Request::METHOD_GET])]
    public function index(Request $request,
                          ProductRepository $productRepository): Response
    {

        $string = $request->query->get('q', 'abcdefg');
        $products = $productRepository->findAll();

        $extensionCheck  = array_reduce(['gd', 'imagick'], function(array $carry, $ext) {
            $carry[$ext] = extension_loaded($ext);
            return $carry;
        }, []);

        foreach ($products as $product) {
//            if ($product->getId() === 100) {
//                echo "x";
//            }
        }

        return $this->render('app/index.html.twig', [
            'symfonyVersion' => KernelAlias::VERSION,
            'string' => $string,
            'products' => $products,
            'extensions' => $extensionCheck,
            'generators' => $this->barcodeService->getGenerators()
        ]);
    }


}
