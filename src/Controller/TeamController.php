<?php

namespace App\Controller;

use App\Entity\Position;
use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\PositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request; 
use App\Service\UploaderPicture;
use App\Service\UploaderCV;


class TeamController extends AbstractController
{
    #[Route('/team', name: 'team' , methods: ['GET'])]
    public function team(TeamRepository $repository, PositionRepository $positionRepository ): Response
    {

        $team11 = $repository->findBy(['hierarchie' => 11]);
        $team10 = $repository->findBy(['hierarchie' => 10]);  // repository pour retrouver tous nos collaborateur dans la BDD dans team
        $team9 = $repository->findBy(['hierarchie' => 9]);
        $team8 = $repository->findBy(['hierarchie' => 8]);

        return $this->render('team/index.html.twig', [
            'team11' => $team11,
            'team10' => $team10, 
            'team9' => $team9,
            'team8' => $team8,
        ]);
    }

    #[Route('team/ad-new', name: 'team_new' , methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, 
     UploaderPicture $uploaderPicture, UploaderCV $uploaderCV ):Response  //   function por crée un collaborateur
    {
        $newTeam = new Team();  // crée un nouveau collaborateur
        $teamForm = $this->createForm(TeamType::class, $newTeam);  // utiliser le formulaire TeamType relié a la BDD team
        $teamForm->handleRequest($request); // vlidation des donné du formulaire
        if ($teamForm->isSubmitted() && $teamForm->isValid()) { // si le formulaire est valid et soumis alors ->
           //  dd($newTeam);

            $position = new Position();
            $position->setLabel($teamForm->get('hierarchie')->getData());

            if($uploaderPicture){  // si on a une photo a télecharger
            $picture = $teamForm->get('imageFile')->getData();  // on récupére les données de  l'image  insérer dans le formulaire 
            
            $image_path = $uploaderPicture->uploadImage($picture);  // image_path variable qui stocke l'image ,
            //  $uploaderPicture est la function qui va gérer le télchargement de l'image ( Service -> UploaderPicture.php),
            // uploadImage($picture) = téléchargement de l'image $picture
            $newTeam->setImageName($image_path); // relie l'image choisi a notre nouveau collaborateur
            }
            // CV PDF // function similaire a la photo mais pour un cv a laide de ( Service -> UploaderCV.php)
            if($uploaderCV){
            $CV = $teamForm->get('CV')->getData();
            
            $CV_path = $uploaderCV->uploadCV($CV);
            $newTeam->setCV($CV_path);
            }
            


            $em->persist($newTeam); // on prépare un nouveu collaborateurs a etre en BDD
            
            $em->flush();  // on enregistre en BDD

            $this->addFlash(  // addFlash va nous retouner un message si la création a été reussie
                'Success',
                'Un nouvelle employer a été ajouter avec succès !'
            );
            return $this->redirectToRoute('team');
        }
        return $this->render('team/new.html.twig',
        ['form' => $teamForm->createView()  // on crée la vue du formulaire pour ajouter un collaborateur dans team/new.html.twig
    ]);
    }

    #[Route('team/edit/{id}', name: 'team_edit' ,methods: ['GET', 'POST'])]  // on indique id pour retrouver un collaborateur grace a son id
    public function edit(int $id, TeamRepository $repository, Request $request, EntityManagerInterface $em, 
      UploaderPicture $uploaderPicture,UploaderCV $uploaderCV ):Response  //   function por modifier un collaborateur
    {
        $edit_Team = $repository->FindOneBy(["id" => $id]);  // repository pour chercher dans la BDD de team -> trouver un par son id
        $form = $this->createForm(TeamType::class, $edit_Team); // utiliser le formulaire TeamType relié a la BDD team
        $form->handleRequest($request); // vlidation des donné du formulaire
        if ($form->isSubmitted() && $form->isValid()) { // si le formulaire est valid et soumis alors ->

            // image de profil

            if($uploaderPicture){
            $picture = $form->get('imageFile')->getData();  // on récupére les données de  l'image  insérer dans le formulaire
            $oldPicture = $edit_Team->getImageName();  // on récupére lancienne image et l'écrasse grace
            //  a la function uploadImage   if($oldPicture) ( Service -> UploaderPicture.php) 
            $image_path = $uploaderPicture->uploadImage($picture, $oldPicture);  // image_path variable qui stocke l'image est détruit lancienne ,
            //  $uploaderPicture est la function qui va gérer le télchargement de l'image ( Service -> UploaderPicture.php),
            // uploadImage($picture) = téléchargement de l'image $picture
            $edit_Team->setImageName($image_path);  // relie l'image choisi a notre collaborateur modifier
            }
            

            // CV PDF // function similaire a la photo mais pour un cv a laide de ( Service -> UploaderCV.php)
            if($uploaderCV){
            $CV = $form->get('CV')->getData();
            $oldCV = $edit_Team->getCV();
            $CV_path = $uploaderCV->uploadCV($CV, $oldCV);
            $edit_Team->setCV($CV_path);
            }
           


            $em->persist($edit_Team); // on prépare la modification du  collaborateurs a etre en BDD
            $em->flush(); // on enregistre en BDD

            $this->addFlash(  // addFlash va nous retouner un message si la modification a été reussie
                'Success',
                'Votre Collaborateurs a été modifié avec succès !'
            );
            return $this->redirectToRoute('team');
            }
            return $this->render('team/edit.html.twig', [
                'form' => $form->createView()  // on crée la vue du formulaire pour modifier un collaborateur dans team/edit.html.twig
            ]);
    }

    #[Route('team/show/{id}', name: 'team_show' , methods: ['GET'])]  
    public function show(TeamRepository $teamRepository,PositionRepository $positionRepository , int $id): Response 
    // function pour afficher sa fiche complète 
    {
       
       $team = $teamRepository->findOneBy(["id" => $id]);  // repository pour chercher dans la BDD de team -> trouver un par son id
       $position = $positionRepository->findOneBy(["id" => $id]);  // repository pour chercher dans la BDD de position -> trouver un par son id

       return $this->render('team/show.html.twig', [
           'team' => $team, // on transmet les données de team
           'position' => $position , // on transmet les données de position
        ]);
   } 

}
