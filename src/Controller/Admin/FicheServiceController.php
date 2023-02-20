<?php

namespace App\Controller\Admin;

use App\Entity\Admin\FicheService;
use App\Entity\Admin\Statut;
use App\Form\Admin\FicheServiceType;
use App\Form\Admin\ServiceType;
use App\Repository\Admin\ClientRepository;
use App\Repository\Admin\FicheServiceRepository;
use App\Repository\Admin\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/ficheservice')]
class FicheServiceController extends AbstractController
{
    #[Route('/', name: 'app_admin_fiche_service_index', methods: ['GET'])]
    public function index(FicheServiceRepository $ficheServiceRepository): Response
    {
        return $this->render('admin/fiche_service/index.html.twig', [
            'fiche_services' => $ficheServiceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_fiche_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FicheServiceRepository $ficheServiceRepository): Response
    {
        $ficheService = new FicheService();
        $form = $this->createForm(FicheServiceType::class, $ficheService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheServiceRepository->save($ficheService, true);

            return $this->redirectToRoute('app_admin_fiche_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/fiche_service/new.html.twig', [
            'fiche_service' => $ficheService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_fiche_service_show', methods: ['GET'])]
    public function show(FicheService $ficheService): Response
    {
        return $this->render('admin/fiche_service/show.html.twig', [
            'fiche_service' => $ficheService,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_fiche_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FicheService $ficheService, FicheServiceRepository $ficheServiceRepository): Response
    {
        $form = $this->createForm(FicheServiceType::class, $ficheService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheServiceRepository->save($ficheService, true);

            return $this->redirectToRoute('app_admin_fiche_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/fiche_service/edit.html.twig', [
            'fiche_service' => $ficheService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_fiche_service_delete', methods: ['POST'])]
    public function delete(Request $request, FicheService $ficheService, FicheServiceRepository $ficheServiceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ficheService->getId(), $request->request->get('_token'))) {
            $ficheServiceRepository->remove($ficheService, true);
        }

        return $this->redirectToRoute('app_admin_fiche_service_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Liste les services consommés par un client
     */
    #[Route('/listByClient/{idclient}', name: 'app_admin_ficheservice_listbyclient', methods: ['GET'])]
    public function listServicesByClient(FicheServiceRepository $ficheServiceRepository, $idclient, ClientRepository $clientRepository)
    {
        // On récupère l'entité l'entité correspondante client
        $client = $clientRepository->find($idclient);
        //dd($client);

        $listservices = $ficheServiceRepository->listByClient($idclient);
        //dd($listservices);

        return $this->render('admin/fiche_service/listbyclient.html.twig', [
            'listservices' => $listservices
        ]);
    }

    /**
     * On ajoute un service sur un client
     **/
    #[Route('/addstatutonclient/{idclient}', name: 'app_admin_ficheservice_addonclient', methods: ['GET', 'POST'])]
    public function addstatutonclient(FicheServiceRepository $ficheServiceRepository, $idclient, ClientRepository $clientRepository, Request $request)
    {
        $user = $this->getUser();
        $client = $clientRepository->find($idclient);
        //dd($user);

        //$ficheservice = $ficheServiceRepository->find($idservice);
        $ficheService = new FicheService();
        $ficheService->setAuthor($user);
        $ficheService->setClient($client);
        $ficheService->setCreatedAt();
        $ficheService->setUpdatedAt();

        $form = $this->createForm(FicheServiceType::class, $ficheService, [
            'action'=> $this->generateUrl('app_admin_ficheservice_addonclient', ['idclient'=> $idclient]),
            'method'=>'POST',
            'attr' => ['class'=>'formaddonclient']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $time = $form->get('time')->getData();
            $echeance = new \DateTime('now');
            $echeance->modify('+'.$time.'year');

            $ficheService->setEcheance($echeance);
            $ficheServiceRepository->save($ficheService, true);

            // on récupére toutes les fiches d'un client
            $ficheservices = $ficheServiceRepository->listByClient($idclient);

            return $this->json([
                'code'=> 200,
                'message' => "Le vendeur a été correctement modifié.",
                // alimente un code html contenant tous les services auquel le client adhère
                'liste' => $this->renderView('admin/fiche_service/_listeficheservice.html.twig', [
                    'listservices' => $ficheservices,
                ])
            ], 200);
        }

        $view = $this->renderForm('admin/fiche_service/_form.html.twig', [
            'ficheservice' => $ficheService,
            'form' => $form
        ]);

        //dd($view->getContent());
        return $this->json([
            'code'=> 200,
            'form' => $view->getContent()
        ], 200);

    }

    #[Route('/del/{id}', name: 'app_admin_del_service')]
    public function deleteServiceClient(FicheService $ficheService, FicheServiceRepository $ficheServiceRepository) : Response
    {
        $idclient = $ficheService->getClient()->getId();
        $ficheServiceRepository->remove($ficheService, true);

        $ficheservices = $ficheServiceRepository->listByClient($idclient);

        return $this->json([
            'code'=> 200,
            'message' => "La fiche service a été correctement supprimée.",
            // alimente un code html contenant tous les services auquel le client adhère
            'liste' => $this->renderView('admin/fiche_service/_listeficheservice.html.twig', [
                'listservices' => $ficheservices,
            ])
        ], 200);
    }
}
