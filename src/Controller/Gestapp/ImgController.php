<?php

namespace App\Controller\Gestapp;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\JpegResponse;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Knp\Snappy\Image;

class ImgController extends AbstractController
{
    #[Route('/gestapp/img', name: 'app_gestapp_img')]
    public function index(): Response
    {
        return $this->render('gestapp/img/index.html.twig', [
            'controller_name' => 'ImgController',
        ]);
    }
    #[Route('/gestapp/imgrender', name: 'app_gestapp_imgrender')]
    public function imageAction(Image $knpSnappyImage)
    {
        $html = $this->renderView('gestapp/img/index.html.twig');

        return new JpegResponse(
            $knpSnappyImage->getOutputFromHtml($html),
            'image.jpg'
        );
    }
}
