<?php

namespace App\Controller\Gestapp;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\JpegResponse;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;

class PdfController extends AbstractController
{
    #[Route('/gestapp/pdf', name: 'app_gestapp_pdf')]
    public function index(): Response
    {
        return $this->render('gestapp/pdf/index.html.twig', [
            'controller_name' => 'PdfController',
        ]);
    }
    #[Route('/gestapp/pdfrender', name: 'app_gestapp_pdfrender')]
    public function pdfAction(Pdf $knpSnappyPdf)
    {
        $html = $this->renderView('gestapp/pdf/index.html.twig', [
            'controller_name' => 'PdfController',
        ]);

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'file.pdf'
        );
    }
}
