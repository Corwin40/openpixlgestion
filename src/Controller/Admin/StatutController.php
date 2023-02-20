<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Statut;
use App\Form\Admin\StatutType;
use App\Repository\Admin\ClientRepository;
use App\Repository\Admin\FicheServiceRepository;
use App\Repository\Admin\StatutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/statut')]
class StatutController extends AbstractController
{
    #[Route('/', name: 'app_admin_statut_index', methods: ['GET'])]
    public function index(StatutRepository $statutRepository): Response
    {
        return $this->render('admin/statut/index.html.twig', [
            'statuts' => $statutRepository->findAll(),
        ]);
    }

    #[Route('/listbyficheservice/{idficheservice}', name: 'app_admin_statut_listbyficheservice', methods: ['GET'])]
    public function listbyficheservice(StatutRepository $statutRepository, $idficheservice): Response
    {
        $statuts = $statutRepository->findBy(['ficheService'=> 2]);
        dd($statuts);

        return $this->render('admin/statut/listbyficheservice.html.twig', [
            'statuts' => $statutRepository->findBy(['ficheService'=> $idficheservice]),
        ]);
    }

    #[Route('/new', name: 'app_admin_statut_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StatutRepository $statutRepository): Response
    {
        $statut = new Statut();
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $statutRepository->save($statut, true);

            return $this->redirectToRoute('app_admin_statut_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/statut/new.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_statut_show', methods: ['GET'])]
    public function show(Statut $statut): Response
    {
        return $this->render('admin/statut/show.html.twig', [
            'statut' => $statut,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_statut_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Statut $statut, StatutRepository $statutRepository): Response
    {
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $statutRepository->save($statut, true);

            return $this->redirectToRoute('app_admin_statut_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/statut/edit.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_statut_delete', methods: ['POST'])]
    public function delete(Request $request, Statut $statut, StatutRepository $statutRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$statut->getId(), $request->request->get('_token'))) {
            $statutRepository->remove($statut, true);
        }

        return $this->redirectToRoute('app_admin_statut_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * On ajoute un statut au service d'un client
     **/
    #[Route('/addstatutonclient/{idficheservice}', name: 'app_admin_statut_addstatutonclient', methods: ['GET', 'POST'])]
    public function addstatutonclient(StatutRepository $statutRepository,FicheServiceRepository $ficheServiceRepository,$idficheservice, Request $request)
    {
        $user = $this->getUser();
        $ficheservice = $ficheServiceRepository->find($idficheservice);
        $client = $ficheservice->getClient();
        //dd($user);

        $statut = new Statut();
        $statut->setAuthor($user->getId());
        $statut->setFicheService($ficheservice);

        $form = $this->createForm(StatutType::class, $statut, [
            'action'=> $this->generateUrl('app_admin_statut_addstatutonclient', ['idficheservice'=> $idficheservice]),
            'method'=>'POST',
            'attr' => ['class'=>'formaddstatutonclient']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $statutRepository->save($statut, true);

            return $this->json([
                'code'=> 200,
                'message' => "OK",
            ], 200);
        }
        $view = $this->renderForm('admin/statut/_formModal.html.twig', [
            'statut' => $statut,
            'form' => $form
        ]);

        //dd($view->getContent());
        return $this->json([
            'code'=> 200,
            'form' => $view->getContent()
        ], 200);

    }
}
