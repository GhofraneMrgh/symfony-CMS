<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Couleurs;
use App\Repository\CouleursRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CouleurController extends AbstractController
{
    /**
     * @Route("/Couleurs", name="app_couleur")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
         $entityManager = $this->getDoctrine()->getManager();
        $couleurs = $entityManager->getRepository(Couleurs::class)->findAll();
        return $this->render('couleur/index.html.twig', [
            'controller_name' => 'CouleurController',
            'couleurs'        => $couleurs,
            'message' => '' ,
        ]);
    }
    /**
     * @Route("/createCouleur", name="createCouleur")
     */
    public function createCouleur(ManagerRegistry $doctrine , Request $request): Response
    {

         $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();
        $couleurs = $entityManager->getRepository(Couleurs::class)->findAll();

        $Couleur = new couleurs();
        $Couleur->setDescription($request->request->get('desc'));
        $Couleur->setStatus($request->request->get('status'));

        // tell Doctrine you want to (eventually) save the Couleur (no queries yet)
        $entityManager->persist($Couleur);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
         
        

        return $this->redirectToRoute('app_couleur', [
            'couleurs'        => $couleurs,
            'message' => 'Saved new Couleur with id '.$Couleur->getId() ,
        ]);

        //return $this->redirectToRoute('app_couleur');

        //return new Response('Saved new Couleur with id '.$Couleur->getId());
    }
    

    /**
     * @Route("/deleteCouleur/{id}", name="deleteCouleur")
     */
    public function deleteCouleur($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
         $couleurs = $entityManager->getRepository(Couleurs::class)->findAll();
        $entity = $entityManager->getRepository('App\Entity\Couleurs')->findOneBy(array('id' => $id));

        if ($entity != null){
            $entityManager->remove($entity);
            $entityManager->flush();
        }
       //return  new RedirectResponse('/couleurs', 302);
        return $this->redirectToRoute('app_couleur', [
            'couleurs'        => $couleurs,
            'message' => 'couleurs supprimé avec succeés'
        ]);
    }

    /**
     * @Route("/editCouleur/{id}")
     */
    public function editCouleur($id , Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $Couleur = $entityManager->getRepository('App\Entity\Couleurs')->find($id);
 
        if (!$Couleur) {
            throw $this->createNotFoundException(
                'Pas de produit avec un id ' . $id
            );
        }
 
        $Couleur->setDescription($request->request->get('desc'));
        $Couleur->setStatus($request->request->get('status'));
        $entityManager->flush();
 
        return $this->redirectToRoute('app_couleur', [
            'message' => 'couleurs modifié avec succeés'
        ]);
    }
}
