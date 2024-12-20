<?php
namespace App\Controller;

use App\Entity\Client;
use App\Entity\Project;
use App\Entity\Deliverable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/home', name: 'app_home')]
    public function index()
    {
        // Récupérer toutes les entités Client, Project, Deliverable depuis la base de données
        $clients = $this->entityManager->getRepository(Client::class)->findAll();
        $projects = $this->entityManager->getRepository(Project::class)->findAll();
        $deliverables = $this->entityManager->getRepository(Deliverable::class)->findAll();

        return $this->render('home/index.html.twig', [
            'clients' => $clients,
            'projects' => $projects,
            'deliverables' => $deliverables,
        ]);
    }
}