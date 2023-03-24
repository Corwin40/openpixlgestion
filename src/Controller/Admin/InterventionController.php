<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Intervention;
use App\Form\Admin\InterventionType;
use App\Form\Admin\StatutType;
use App\Repository\Admin\ClientRepository;
use App\Repository\Admin\FicheServiceRepository;
use App\Repository\Admin\InterventionRepository;
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
        return $this->render('admin/intervention/index.html.twig', [
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

        return $this->renderForm('admin/intervention/new.html.twig', [
            'intervention' => $intervention,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_intervention_show', methods: ['GET'])]
    public function show(Intervention $intervention): Response
    {
        return $this->render('admin/intervention/show.html.twig', [
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
            $interventionRepository->save($intervention, true);

            return $this->redirectToRoute('app_admin_intervention_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/intervention/edit.html.twig', [
            'intervention' => $intervention,
            'form' => $form,
        ]);
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
            $interventionRepository->save($intervention, true);

            // Fonction d'envoi mail
            $email = (new Email())
                ->from('contact@openpixl.fr')
                ->to($emailClient)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('[OpenPixl] - Mise à jour de votre service')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');

            $mailer->send($email);

            return $this->json([
                'code'=> 200,
                'message' => "l'intervention a bien été enregistrée.",
            ], 200);
        }
        $view = $this->renderForm('admin/intervention/_form.html.twig', [
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
        // On récupère l'entité correspondante ficheservice
        $fiche = $ficheServiceRepository->find($idficheservice);

        $listinterves = $interventionRepository->listeintervebyclient($idficheservice);
        //dd($listinterves);

        return $this->json([
            'code'=> 200,
            'message' => "OK",
            'list' => $this->renderView('admin/intervention/listeintervebyclient.html.twig', [
                'listeinterves' => $listinterves,
            ])
        ], 200);
    }
}
