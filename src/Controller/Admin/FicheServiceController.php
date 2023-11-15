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

    #[Route('/new/{idclient}', name: 'app_admin_fiche_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FicheServiceRepository $ficheServiceRepository, $idclient, ClientRepository $clientRepository): Response
    {
        $user = $this->getUser();
        $client = $clientRepository->find($idclient);
        //dd($user);

        $ficheService = new FicheService();
        $ficheService->setAuthor($user);
        $ficheService->setClient($client);
        $ficheService->setCreatedAt();
        $ficheService->setUpdatedAt();

        $form = $this->createForm(FicheServiceType::class, $ficheService, [
            "action" => $this->generateUrl('app_admin_fiche_service_new', ['idclient' => $idclient]),
            "method" => 'POST',
            'attr' => [
                'class' => 'formFiche'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $engagement = $form->get('engagement')->getData();
            $inscription = new \DateTime('now');
            $echeance = date_add($inscription, $engagement);

            $ficheService->setEcheance($echeance);
            $ficheServiceRepository->save($ficheService, true);

            $fiches = $ficheServiceRepository->listByClient($idclient);

            //return $this->redirectToRoute('app_admin_fiche_service_index', [], Response::HTTP_SEE_OTHER);
            return $this->json([
                'code' => 200,
                'message' => 'La page a été crée avec success.',
                'liste' => $this->renderView('admin/fiche_service/_listeficheservice.html.twig', [
                    'listficheservices' => $fiches
                ])
            ], 200);
        }

        $view = $this->render('admin/fiche_service/_form.html.twig', [
            'ficheService' => $ficheService,
            'form' => $form
        ]);

        return $this->json([
            'code' => 200,
            'message' => 'Le service a bien été ajouté sur le compte du client.',
            'formView' => $view->getContent()
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
        $form = $this->createForm(FicheServiceType::class, $ficheService, [
            'action'=> $this->generateUrl('app_admin_fiche_service_edit', ['id'=> $ficheService->getId()]),
            'method'=>'POST',
            'attr' => ['class' => 'formFiche']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $ficheService->getClient();
            $ficheServiceRepository->save($ficheService, true);
            $fiches = $ficheServiceRepository->listByClient($client->getId());

            //return $this->redirectToRoute('app_admin_fiche_service_index', [], Response::HTTP_SEE_OTHER);
            return $this->json([
                'code' => 200,
                'message' => 'La page a été crée avec success.',
                'liste' => $this->renderView('admin/fiche_service/_listeficheservice.html.twig', [
                    'listficheservices' => $fiches
                ])
            ], 200);
        }

        $view = $this->render('admin/fiche_service/_form.html.twig', [
            'ficheservice' => $ficheService,
            'form' => $form
        ]);

        return $this->json([
            'code' => 200,
            'message' => 'Le service a été correctement modifié.',
            'form' => $view->getContent()
        ], 200);

        // return $this->renderForm('admin/fiche_service/edit.html.twig', [
        //    'fiche_service' => $ficheService,
        //    'form' => $form,
        //]);
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

        $listficheservices = $ficheServiceRepository->listByClient($idclient);

        return $this->render('admin/fiche_service/listbyclient.html.twig', [
            'listficheservices' => $listficheservices
        ]);
    }

    #[Route('/listservactifs/{idserv}', name: 'app_admin_service_index', methods: ['GET'])]
    public function listservactifs(FicheServiceRepository $ficheServiceRepository, $idserv): Response
    {
        $listficheservices = $ficheServiceRepository->listByServ($idserv);

        return $this->render('admin/fiche_service/listservactif.html.twig', [
            'listficheservices' => $listficheservices
        ]);
    }

    /**
     * On ajoute une fiche service depuis un compte client
     **/
    #[Route('/addonclient/{idclient}', name: 'app_admin_ficheservice_addonclient', methods: ['GET', 'POST'])]
    public function addonclient(FicheServiceRepository $ficheServiceRepository, $idclient, ClientRepository $clientRepository, Request $request)
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
            $engagement = $form->get('engagement')->getData();
            $inscription = new \DateTime('now');
            $echeance = date_add($inscription, $engagement);

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
