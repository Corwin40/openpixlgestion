<?php

namespace App\Controller\Gestapp;

use App\Entity\Gestapp\Intervention;
use App\Form\Admin\InterventionType;
use App\Repository\Gestapp\FicheServiceRepository;
use App\Repository\Gestapp\InterventionRepository;
use Container5c9QE5J\getClientRepositoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/intervention')]
class InterventionController extends AbstractController
{
    #[Route('/', name: 'app_admin_intervention_index', methods: ['GET'])]
    public function index(InterventionRepository $interventionRepository): Response
    {
        return $this->render('gestapp/intervention/index.html.twig', [
            'interventions' => $interventionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_intervention_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InterventionRepository $interventionRepository): Response
    {
        $intervention = new Intervention();
        $form = $this->createForm(InterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $startedAt = $form->getData()->get('startedAt');
            $finishedAt = $form->getData()->get('finishedAt');
            $delta = date_diff($startedAt, $finishedAt);

            $interventionRepository->save($intervention, true);

            return $this->redirectToRoute('app_admin_intervention_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestapp/intervention/new.html.twig', [
            'intervention' => $intervention,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_intervention_show', methods: ['GET'])]
    public function show(Intervention $intervention): Response
    {
        return $this->render('gestapp/intervention/show.html.twig', [
            'intervention' => $intervention,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_intervention_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Intervention $intervention, InterventionRepository $interventionRepository): Response
    {
        $form = $this->createForm(InterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $startedAt = $form->get('startedAt')->getData();
            $finishedAt = $form->get('finishedAt')->getData();
            $intervention->setTimelaps($delta = date_diff($startedAt, $finishedAt));
            // calcul volume
            $interval = strtotime("1970/01/01".$intervention->getTimelaps()->format('%H:%i:%s'));
            $vol = $interval * ($intervention->getFicheservice()->getPriceHour() / 3600);
            $intervention->setVolume($vol);
            $interventionRepository->save($intervention, true);

            return $this->redirectToRoute('app_admin_intervention_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestapp/intervention/edit.html.twig', [
            'intervention' => $intervention,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/duplicate', name: 'app_admin_intervention_duplicate', methods: ['GET', 'POST'])]
    public function duplicate(Request $request, Intervention $intervention, InterventionRepository $interventionRepository): Response
    {
        $name = $intervention->getName().'- copie';
        $newIntervention = clone($intervention);
        $newIntervention->setName($name);
        $interventionRepository->save($newIntervention, true);
        return $this->json([
            'code' => 200,
            'message' => 'ligne dupliquée'
        ],200);
    }

    #[Route('/{id}', name: 'app_admin_intervention_delete', methods: ['POST'])]
    public function delete(Request $request, Intervention $intervention, InterventionRepository $interventionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$intervention->getId(), $request->request->get('_token'))) {
            $interventionRepository->remove($intervention, true);
        }

        return $this->redirectToRoute('app_admin_intervention_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * On ajoute une intervention au service d'un client
     **/
    #[Route('/addinterveonclient/{idficheservice}', name: 'app_admin_intervention_addinterveonclient', methods: ['GET', 'POST'])]
    public function addinterveonclient(
        InterventionRepository $interventionRepository,
        FicheServiceRepository $ficheServiceRepository,
        $idficheservice,
        Request $request,
        MailerInterface $mailer
    )
    {
        $user = $this->getUser();

        $ficheservice = $ficheServiceRepository->find($idficheservice);
        $nameserv = $ficheservice->getService()->getCode();
        $emailClient = $ficheservice->getClient()->getEmail();
        $dateNow = new \DateTime('now');
        $name = $nameserv.'-'.$dateNow->format('dmY');

        $intervention = new Intervention();
        $intervention->setName($name);
        $intervention->setAuthor($user);
        $intervention->setFicheService($ficheservice);

        $form = $this->createForm(InterventionType::class, $intervention, [
            'action'=> $this->generateUrl('app_admin_intervention_addinterveonclient', ['idficheservice'=> $idficheservice]),
            'method'=>'POST',
            'attr' => ['class'=>'formaddinterventiononclient']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $startedAt = $form->get('startedAt')->getData();
            $finishedAt = $form->get('finishedAt')->getData();
            $intervention->setTimelaps($delta = date_diff($startedAt, $finishedAt));
            // calcul volume
            $interval = strtotime("1970/01/01".$intervention->getTimelaps()->format('%H:%i:%s'));
            $vol = $interval * ($intervention->getFicheservice()->getPriceHour() / 3600);
            $intervention->setVolume($vol);
            $interventionRepository->save($intervention, true);

            // Fonction d'envoi mail
            $email = (new Email())
                ->from('contact@openpixl.fr')
                ->to($emailClient)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('[OpenPixl] - Mise à jour de votre service : '.$nameserv)
                ->text('Sending emails is fun again!')
                ->html('fichier twig + Variable objet à transmettre');

            $mailer->send($email);

            return $this->json([
                'code'=> 200,
                'message' => "l'intervention a bien été enregistrée.",
            ], 200);
        }
        $view = $this->render('gestapp/intervention/_form.html.twig', [
            'intervention' => $intervention,
            'form' => $form
        ]);

        //dd($view->getContent());
        return $this->json([
            'code'=> 200,
            'form' => $view->getContent()
        ], 200);

    }

    /**
     * On ajoute une intervention au service d'un client
     **/
    #[Route('/{id}/editinterveonclient/{idficheservice}', name: 'app_admin_intervention_editinterveonclient', methods: ['GET', 'POST'])]
    public function editinterveonclient(
        Intervention $intervention,
        $idficheservice,
        InterventionRepository $interventionRepository,
        FicheServiceRepository $ficheServiceRepository,
        Request $request,
        MailerInterface $mailer
    )
    {
        $user = $this->getUser();

        $ficheservice = $ficheServiceRepository->find($idficheservice);
        $nameserv = $ficheservice->getService()->getCode();
        $emailClient = $ficheservice->getClient()->getEmail();

        $form = $this->createForm(InterventionType::class, $intervention, [
            'action'=> $this->generateUrl('app_admin_intervention_editinterveonclient', [
                'id'=> $intervention->getId(),
                'idficheservice'=> $idficheservice
            ]),
            'method'=>'POST',
            'attr' => ['class'=>'forminterventiononclient']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd('dans la validation du form');
            $startedAt = $form->get('startedAt')->getData();
            $finishedAt = $form->get('finishedAt')->getData();
            $intervention->setTimelaps($delta = date_diff($startedAt, $finishedAt));
            // calcul volume
            $interval = strtotime("1970/01/01".$intervention->getTimelaps()->format('%H:%i:%s'));
            $vol = $interval * ($intervention->getFicheservice()->getPriceHour() / 3600);
            $intervention->setVolume($vol);
            $interventionRepository->save($intervention, true);

            return $this->json([
                'code'=> 200,
                'message' => "l'intervention a bien été enregistrée.",
            ], 200);
        }

        $view = $this->render('gestapp/intervention/_form.html.twig', [
            'intervention' => $intervention,
            'form' => $form
        ]);

        //dd($view->getContent());
        return $this->json([
            'code'=> 200,
            'form' => $view->getContent()
        ], 200);

    }


    #[Route('/listeinterveonclient/{idficheservice}', name: 'app_admin_intervention_listeinterveonclient', methods: ['GET', 'POST'])]
    public function listeinterveonclient(InterventionRepository $interventionRepository,$idficheservice, FicheServiceRepository $ficheServiceRepository, Request $request)
    {
        $listinterves = $interventionRepository->listeintervebyclient($idficheservice);
        $ficheService = $ficheServiceRepository->find($idficheservice);

        return $this->json([
            'code'=> 200,
            'message' => "OK",
            'list' => $this->renderView('gestapp/intervention/listeintervebyclient.html.twig', [
                'listeinterves' => $listinterves,
                'idficheservice' =>$idficheservice,
                'fiche' => $ficheService
            ])
        ], 200);
    }
}
