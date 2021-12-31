<?php

namespace App\Controller;

use App\Entity\Request;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'homepage')]
    public function index(HttpFoundationRequest $request): Response
    {
        $track = new Request();
        $track->setIp($request->getClientIp());
        $track->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($track);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'I am tracking you!!!',
        ]);
    }
}
