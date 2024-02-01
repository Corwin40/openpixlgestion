<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'op_admin_dashboard')]
    public function index(): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if($hasAccess){
            return $this->redirectToRoute('op_admin_dashboard_home');
        }

        return $this->redirectToRoute('op_security_login');
    }

    #[Route('/admin', name: 'op_admin_dashboard_home')]
    public function dashboard()
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

}
