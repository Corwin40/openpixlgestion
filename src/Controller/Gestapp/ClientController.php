<?php

namespace App\Controller\Gestapp;

use App\Entity\gestapp\Client;
use App\Entity\gestapp\TypeClient;
use App\Form\Admin\ClientType;
use App\Repository\Gestapp\ClientRepository;
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
        return $this->render('gestapp/client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    /**
     * Sélectionne tous les clients favoris
     * @param ClientRepository $clientRepository
     * @return Response
     */
    #[Route('/showfavoris', name: 'app_admin_client_showfavoris', methods: ['GET'])]
    public function showFavoris(ClientRepository $clientRepository): Response
    {
        return $this->render('gestapp/client/showfavoris.html.twig', [
            'clients' => $clientRepository->findBy(['isFavori' => 1]),
        ]);
    }

    #[Route('/setfavoris/{id}', name: 'app_admin_client_setfavoris', methods: ['GET', 'POST'])]
    public function setFavoris(Client $client, ClientRepository $clientRepository): Response
    {
        $admin = $this->getUser();
        $isFavori = $client->isIsFavori();
        if(!$admin) return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        // Si la page est déja publiée, alors on dépublie

        if($isFavori == true){
            $client->setIsFavori(0);
            //dd($client);
            $clientRepository->save($client, true);
            return $this->json(['code'=> 200, 'message' => "Le client est désépinglé au tableau de bord"], 200);
        }
        $client->setIsFavori(1);
        //dd($client);
        $clientRepository->save($client, true);
        return $this->json(['code'=> 200, 'message' => "Le client est épinglé au tableau de bord"], 200);
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

        return $this->renderForm('gestapp/client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('gestapp/client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/getTypeClient/{id}', name: 'app_admin_client_gettypeclient', methods: ['GET'])]
    public function getTypeClient(TypeClient $typeClient): Response
    {
        if($typeClient->isIsFormCompleted()){
            $val = 1;
        }else{
            $val = 0;
        }

        return $this->json([
            'code'=> 200,
            'message' => "Le vendeur a été correctement modifié.",
            // alimente un code html contenant tous les services auquel le client adhère
            'value' => $val
        ], 200);
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

        return $this->renderForm('gestapp/client/edit.html.twig', [
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
