<?php

namespace App\Controller\Gestapp;

use App\Entity\Gestapp\FicheService;
use App\Entity\Gestapp\Intervention;
use App\Entity\Gestapp\Invoice;
use App\Entity\Gestapp\InvoiceItem;
use App\Form\Gestapp\InvoiceType;
use App\Repository\Gestapp\ClientRepository;
use App\Repository\Gestapp\FicheServiceRepository;
use App\Repository\Gestapp\InterventionRepository;
use App\Repository\Gestapp\InvoiceItemRepository;
use App\Repository\Gestapp\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestapp/invoice')]
class InvoiceController extends AbstractController
{
    #[Route('/', name: 'app_gestapp_invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        return $this->render('gestapp/invoice/index.html.twig', [
            'invoices' => $invoiceRepository->findAll(),
        ]);
    }

    #[Route('/new/{idFiche}', name: 'app_gestapp_invoice_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager, 
        $idFiche, 
        InvoiceRepository $invoiceRepository, 
        FicheServiceRepository $ficheServiceRepository, 
        InterventionRepository $interventionRepository, 
        InvoiceItemRepository $invoiceItemRepository
        ): Response
    {   
        
        $data = json_decode($request->getContent(), true);
        $arrayCheckboxes = $request->request->get('arrayCheckboxes');
        dd($request->request->get('arrayCheckboxes'));
        $arrayCheckboxes = $data['arrayCheckboxes'];
        //dd($arrayCheckboxes);
        $interventions = [];
        $total = 0;
        foreach ($arrayCheckboxes as $a) {
            $intervention = $interventionRepository->find($a);
            $price = $intervention->getVolume();
            array_push($interventions, $intervention);
            $total = $total + $price;
        }
        
        //recuperation de la fiche service
        $fiche = $ficheServiceRepository->find($idFiche);
        //dd($fiche);
        $descriptif = $fiche->getDescriptif();
        $name = $fiche->getName();
        $client = $fiche->getClient();

        //creation du numéro de facture
        $invoices = $invoiceRepository->findAll();
        $num = 0;
        if(!$invoices){
            $num = ++$num;
        }else{
            $lastInvoice = end($invoices);
            $lastNum = $lastInvoice->getNum();
            $num = ++$lastNum;
            dd($num);
        }

        $invoice = new Invoice();
        //dd($name, $descriptif);
        $invoice->setInvoiceAt(new \DateTime('now'));
        $invoice->setNum($num);
        //$invoice->setDescriptif($descriptif);
        //$invoice->setName($name);
        $invoice->setRefCustomer($client);

        // Création des items de la facture
        //$data = json_decode($request->getContent(), true);
        //dd($data);
        //foreach ($arrayCheckboxes as $idIntervention) {
        //    $intervention = $interventionRepository->find($idIntervention);
        //    //$invoiceItem = new InvoiceItem();
        //     dd($intervention);
        //}
                            
        $form = $this->createForm(InvoiceType::class, $invoice, [
            'action' => $this->generateUrl('app_gestapp_invoice_new', [
                'idFiche' => $idFiche
            ]),
            'method' => 'POST',
            'attr' => [
                'id' => 'formInvoice'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Créez une nouvelle facture
            $invoice = new Invoice();
            foreach($interventions as $i){
                $invoiceItem = new InvoiceItem();
                $invoiceItem->setName($intervention->getName());
                $invoiceItem->setHour($intervention->getTimelaps());
                $invoiceItem->setRefInvoice($invoice);
                // Définissez le total et la TVA de la facture
                $invoiceItem->setMontantHt($intervention->getVolume());
                $invoiceItem->setMontantTtc($intervention->getVolume()*$fiche->getTva());

                //dd($invoiceItem);
            }
            
        
            // Persistez la facture
            $entityManager->persist($invoice);
            $entityManager->flush();
        
            return $this->json([
                'code'=> 200,
                'message' => "L'intervention et les éléments de facture ont bien été enregistrés.",
            ], 200);
        }        
        $invoiceItems = $invoiceItemRepository->findby(['refInvoice' => $invoice, 'id' => 'ASC']);

        $view = $this->render('gestapp/invoice/new.html.twig', [
            'fiche' => $fiche,
            'invoice' => $invoice,
            'items' => $invoiceItems,
            'interventions' => $interventions,
            'form' => $form,
        ]);

        //dd($view->getContent());
        return $this->json([
            'code'=> 200,
            'form' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}', name: 'app_gestapp_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        return $this->render('gestapp/invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestapp_invoice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_gestapp_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gestapp/invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gestapp_invoice_delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invoice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gestapp_invoice_index', [], Response::HTTP_SEE_OTHER);
    }
}