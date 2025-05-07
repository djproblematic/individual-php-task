<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BookController extends AbstractController
{
    #[Route('/api/books', name: 'create_book', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $title = $data['title'] ?? null;
        $authorName = $data['author'] ?? null;

        if (!$title || !$authorName) {
            return $this->json(['error' => 'Missing title or author'], 400);
        }

        $author = new Author();
        $author->setName($authorName);
        $em->persist($author);

        $book = new Book();
        $book->setTitle($title);
        $book->setAuthor($author);
        $em->persist($book);

        $em->flush();

        return $this->json(['message' => 'Book created']);
    }
}
