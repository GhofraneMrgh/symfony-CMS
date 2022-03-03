<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produits;
use App\Entity\Couleurs;
use App\Entity\Tailles;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProduitsController extends AbstractController
{
    /**
     * @Route("/produits", name="app_produits")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $this->getDoctrine()->getManager();
        $produits = $entityManager->getRepository(Produits::class)->findAll();
        $couleurs = $entityManager->getRepository(Couleurs::class)->findAll();
        $tailles = $entityManager->getRepository(Tailles::class)->findAll();

        return $this->render('produits/index.html.twig', [
            'controller_name' => 'ProduitsController',
            'produits'        => $produits,
            'couleurs'        => $couleurs,
            'tailles'        => $tailles,
        ]);
    }

    /**
     * @Route("/createProduct", name="createProduct")
     */
    public function createProduct(ManagerRegistry $doctrine , Request $request): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();

        $product = new Produits();
        $product->setDescription($request->request->get('desc'));
        $product->setPrix($request->request->get('prix'));
        $product->setTaille($request->request->get('taille'));
        $product->setCouleurs($request->request->get('couleur'));
        $product->setSexe($request->request->get('sexe'));
        $product->setQt($request->request->get('qt'));
        $product->setStatus($request->request->get('status'));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->redirectToRoute('app_produits', [
            'message' => 'Saved new product with id '.$product->getId()
        ]);
    }
    

    /**
     * @Route("/deleteProduct/{id}", name="deleteProduct")
     */
    public function deleteProduct($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $entity = $entityManager->getRepository('App\Entity\Produits')->findOneBy(array('id' => $id));

        if ($entity != null){
            $entityManager->remove($entity);
            $entityManager->flush();
        }
       //return  new RedirectResponse('/produits', 302);
        return $this->redirectToRoute('app_produits', [
            'message' => 'Produits supprimé avec succeés'
        ]);
    }

    /**
     * @Route("/editProduct/{id}", name="editProduct")
     */
    public function editProduct($id , Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository('App\Entity\Produits')->find($id);
 
        if (!$product) {
            throw $this->createNotFoundException(
                'Pas de produit avec un id ' . $id
            );
        }
 
        $product->setDescription($request->request->get('desc'));
        $product->setPrix($request->request->get('prix'));
        $product->setTaille($request->request->get('taille'));
        $product->setCouleurs($request->request->get('couleur'));
        $product->setSexe($request->request->get('sexe'));
        $product->setQt($request->request->get('qt'));
        $product->setStatus($request->request->get('status'));
        $entityManager->flush();
 
        return $this->redirectToRoute('app_produits', [
            'message' => 'Produits modifié avec succeés'
        ]);
    }
}
