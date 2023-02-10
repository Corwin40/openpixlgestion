<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Client;
use App\Entity\Admin\FicheService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DeleteListServiceController extends AbstractController
{
    #[Route('/del/{id}', name: 'app_admin_del_service')]
    public function deleteListServiceAction()
    {


        // Supprimer la fiche service de la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($listservice);
        $entityManager->flush();

        // Rediriger l'utilisateur vers la liste des fiches services du client
        return $this->render('admin/delete_list_service/index.html.twig', [
            'controller_name' => 'DeleteListServiceController',
        ]);
    }
}
