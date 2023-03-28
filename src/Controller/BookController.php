<?php

namespace App\Controller;
use App\Entity\Book;
use App\Service\CalculationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

// toDo Можешь 2 контроллера сделать, по 4 метода создание/чтение/обновление/удаление
// По книгам можно 2 метода на чтение сделать. Вытащить все записи и одну по id.
class BookController extends AbstractController
{
    // get book by id
    /**
     * @Route("/api1/books/{id}",methods={"GET"})
     */
    public function getBookById(EntityManagerInterface $entityManager, int $id): Response
    {
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        return new Response($book);
    }

    /**
     * @Route("/api1/books",methods={"GET"})
     */
    public function getBooks(EntityManagerInterface $entityManager): Response
    {
        $books = $entityManager->getRepository(Book::class)->findAll();

        if (!$books) {
            throw $this->createNotFoundException(
                'No books found'
            );
        }

        return new Response($books);
    }

    // create entity
    /**
     * @Route("/api1/books/create",methods={"POST"})
     */
    public function addBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data= $request->toArray();
        $bookName = $data["book"];
        $category = $data["category"];

        $book = new Book();
        $book->setName($bookName);
        $book->setCategory($category);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }
}
