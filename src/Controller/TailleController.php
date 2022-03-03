<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tailles;
use App\Repository\TaillesRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TailleController extends AbstractController
{
    /**
     * @Route("/Tailles", name="app_taille")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $tailles = $entityManager->getRepository(Tailles::class)->findAll();
        return $this->render('taille/index.html.twig', [
            'controller_name' => 'TailleController',
            'tailles'        => $tailles,
        ]);
    }

    /**
     * @Route("/createTaille", name="createTaille")
     */
    public function createTaille(ManagerRegistry $doctrine , Request $request): Response
    {

         $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();

        $Taille = new Tailles();
        $Taille->setDescription($request->request->get('desc'));
        $Taille->setStatus($request->request->get('status'));

        // tell Doctrine you want to (eventually) save the Taille (no queries yet)
        $entityManager->persist($Taille);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        //return new Response('Saved new Taille with id '.$Taille->getId());
        return $this->redirectToRoute('app_taille', [
            'message' => 'Saved new Taille with id '.$Taille->getId() ,
        ]);
    }
    

    /**
     * @Route("/deleteTaille/{id}", name="deleteTaille")
     */
    public function deleteTaille($id)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $entity = $entityManager->getRepository('App\Entity\Tailles')->findOneBy(array('id' => $id));

        if ($entity != null){
            $entityManager->remove($entity);
            $entityManager->flush();
        }
       //return  new RedirectResponse('/Tailles', 302);
        return $this->redirectToRoute('app_taille', [
            'message' => 'Tailles supprimé avec succeés'
        ]);
    }

    /**
     * @Route("/editTaille/{id}")
     */
    public function editTaille($id , Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $Taille = $entityManager->getRepository('App\Entity\Tailles')->find($id);
 
        if (!$Taille) {
            throw $this->createNotFoundException(
                'Pas de taille avec un id ' . $id
            );
        }
 
        $Taille->setDescription($request->request->get('desc'));
        $Taille->setStatus($request->request->get('status'));
        $entityManager->flush();
 
        return $this->redirectToRoute('app_taille', [
            'message' => 'Tailles modifié avec succeés'
        ]);
    }
}
