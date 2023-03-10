<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Client;
use App\Form\Admin\ClientType;
use App\Repository\Admin\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'app_admin_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('admin/client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClientRepository $clientRepository, SluggerInterface $slugger): Response
    {
        // on récupère l'utilisateur connecté
        $user = $this->getUser();

        // on hydrate l'entité
        $client = new Client();

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $client->setMembers($user);

            /** @var UploadedFile $logoFile */
            $logoFileName = $form->get('logoFile')->getData();
            if ($logoFileName) {
                $originallogoFileName = pathinfo($logoFileName->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safelogoFileName = $slugger->slug($originallogoFileName);
                $newlogoFileName = $safelogoFileName . '-' . uniqid() . '.' . $logoFileName->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $logoFileName->move(
                        $this->getParameter('logo_directory'),
                        $newlogoFileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $client->setLogoName($newlogoFileName);
            }

            // Sauvegarde en BDD l'entité
            $clientRepository->save($client, true);
            // Redirection
            return $this->redirectToRoute('app_admin_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('admin/client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, ClientRepository $clientRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Suppression directe du logo
            $supprLogoInput = $form->get('isSupprLogo')->getData();
            if($supprLogoInput && $supprLogoInput == true){
                // récupération du nom de l'image
                $logoName = $client->getLogoName();
                $pathheader = $this->getParameter('logo_directory').'/'.$logoName;
                // On vérifie si l'image existe
                if(file_exists($pathheader)){
                    unlink($pathheader);
                }
                $client->setLogoName(null);
                $client->setIsSupprLogo(0);
            }

            /** @var UploadedFile $logoFile **/
            $logoFileInput = $form->get('logoFile')->getData();
            if ($logoFileInput) {
                //dd($logoFileInput);
                // Effacement du fichier bannièreFileName si il est présent en BDD
                // récupération du nom de l'image
                $logoName = $client->getLogoName();
                // suppression du Fichier
                if($logoName){
                    $pathlogo = $this->getParameter('logo_directory').'/'.$logoName;
                    // On vérifie si l'image existe
                    if(file_exists($pathlogo)){
                        unlink($pathlogo);
                    }
                }

                $originallogoFilename = pathinfo($logoFileInput->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safelogoFilename = $slugger->slug($originallogoFilename);
                $newlogoFilename = $safelogoFilename . '-' . uniqid() . '.' . $logoFileInput->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $logoFileInput->move(
                        $this->getParameter('logo_directory'),
                        $newlogoFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $client->setLogoName($newlogoFilename);
            }

            $clientRepository->save($client, true);

            return $this->redirectToRoute('app_admin_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $clientRepository->remove($client, true);
        }

        return $this->redirectToRoute('app_admin_client_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/suppr/{id}', name: 'app_admin_client_suppr', methods: ['POST'])]
    public function suppr(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        

        $clientRepository->remove($client);
        return $this->redirectToRoute('app_admin_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
