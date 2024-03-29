<?php

namespace App\Controller\Gestapp;

use App\Entity\Gestapp\Service;
use App\Form\Admin\ServiceType;
use App\Repository\Gestapp\ClientRepository;
use App\Repository\Gestapp\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_admin_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('gestapp/service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ServiceRepository $serviceRepository): Response
    {
        $user = $this->getUser();
        //dd($user);
        $service = new Service();

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->setMembers($user);
            $serviceRepository->save($service, true);

            return $this->redirectToRoute('app_admin_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestapp/service/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/newonclient/{idclient}', name: 'app_admin_service_newonclient', methods: ['GET', 'POST'])]
    public function newonclient(Request $request, ServiceRepository $serviceRepository, $idclient, ClientRepository $clientRepository)
    {
        $user = $this->getUser();
        $client = $clientRepository->find($idclient);
        //dd($user);
        $service = new Service();
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

        return $this->renderForm('gestapp/service/newonclient.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_admin_service_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        return $this->render('gestapp/service/show.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, ServiceRepository $serviceRepository): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->get('duration')->getData());
            $serviceRepository->save($service, true);

            return $this->redirectToRoute('app_admin_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestapp/service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, ServiceRepository $serviceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $serviceRepository->remove($service, true);
        }

        return $this->redirectToRoute('app_admin_service_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/listByService/{idclient}', name: 'app_admin_service_listByService', methods: ['GET'])]
    public function listByService(ServiceRepository $serviceRepository, $idclient, ClientRepository $clientRepository)
    {
        $client = $serviceRepository->find($idclient);
        //dd($client);

        $services = $serviceRepository->listByService($idservice);
        //dd($services);

        return $this->render('gestapp/service/Listbyservice.html.twig', [
            'services' => $services
        ]);
    }
}
