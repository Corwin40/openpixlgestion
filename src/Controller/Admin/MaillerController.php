<?php

namespace App\Controller\Admin;

use App\Repository\Admin\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaillerController extends AbstractController
{
    #[Route('/admin/mailler', name: 'app_admin_mailler')]
    public function index(): Response
    {
        return $this->render('admin/mailler/index.html.twig', [
            'controller_name' => 'MaillerController',
        ]);
    }
    #[Route('/admin/mailler/mail', name: 'app_admin_mailler_mail')]
    public function mail(ClientRepository $clientRepository): Response
    {

        return $this->render('mail/mail.html.twig', [
            'controller_name' => 'MaillerController',
        ]);
    }
}
