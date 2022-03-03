<?php
 
namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Produits;
use App\Entity\Couleurs;
use App\Entity\Tailles;

 
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produits = $entityManager->getRepository(Produits::class)->findAll();
        $couleurs = $entityManager->getRepository(Couleurs::class)->findAll();
        $tailles = $entityManager->getRepository(Tailles::class)->findAll();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'produits'        => $produits,
            'couleurs'        => $couleurs,
            'tailles'        => $tailles,
        ]);
    }
}