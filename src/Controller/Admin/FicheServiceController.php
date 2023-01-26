<?php

namespace App\Controller\Admin;

use App\Entity\Admin\FicheService;
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

        return $this->render('admin/service/listbyclient.html.twig', [
            'listservices' => $listservices
        ]);
    }

    /**
     * On ajoute un service sur un client
     **/
    #[Route('/addonclient/{idclient}', name: 'app_admin_ficheservice_addonclient', methods: ['GET'])]
    public function addonclient(FicheServiceRepository $ficheServiceRepository, $idclient, ClientRepository $clientRepository)
    {
        $user = $this->getUser();
        $client = $clientRepository->find($idclient);
        //dd($user);

        $ficheservice = $ficheServiceRepository->find($idservice);

        $service->setMembers($user);
        $service->addClient($client);
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $serviceRepository->save($service, true);

            return $this->render('admin/client/show.html.twig', [
                'id' => $idclient,
            ]);
        }

        //dd($form->isValid());

        return $this->renderForm('admin/service/addonclient.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }
}
