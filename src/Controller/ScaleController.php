<?php

namespace App\Controller;

use App\Entity\Scale;
use App\Repository\ScaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/scale")
 */
class ScaleController extends AbstractController
{
    private ScaleRepository $scaleRepository;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ScaleRepository $scaleRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ) {
        $this->scaleRepository = $scaleRepository;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->json(
            $this->scaleRepository->findAll()
        );
    }

    /**
     * @Route("/{id}")
     */
    public function getScale(Scale $scale): Response
    {
        return $this->json($scale);
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function postScale(Request $request): Response
    {
        $scale = $this->serializer->deserialize($request->getContent(), Scale::class, 'json');

        $this->entityManager->persist($scale);
        $this->entityManager->flush();

        return $this->json($scale);
    }
}
