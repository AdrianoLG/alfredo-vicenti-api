<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private $bookService;
    private $request;

    public function __construct(BookService $bookService, Request $request)
    {
        $this->bookService = $bookService;
        $this->request = $request;
    }

    public function createBook()
    {
        if (!$this->request->has('user_id')) {
            return $this->missingFieldResponse('user_id');
        }
        if (!$this->request->has('title')) {
            return $this->missingFieldResponse('title');
        }
        if (!$this->request->has('author')) {
            return $this->missingFieldResponse('author');
        }
        if (!$this->request->has('category')) {
            return $this->missingFieldResponse('category');
        }

        $book = $this->bookService->postBook($this->request->all());

        return $this->successResponse(201, 'Book succesfully created', $book);
    }

    public function getBooks(int $user_id)
    {
        $books = $this->bookService->getBooks($user_id);

        return $this->successResponse(200, null, $books);
    }

    public function getBook(int $book_id, int $user_id)
    {
        $book = $this->bookService->getBook($book_id, $user_id);

        return $this->successResponse(200, null, $book);
    }

    public function updateBook(int $book_id)
    {
        if (!$this->request->has('user_id')) {
            return $this->missingFieldResponse('user_id');
        }
        if (!$this->request->has('title')) {
            return $this->missingFieldResponse('title');
        }
        if (!$this->request->has('author')) {
            return $this->missingFieldResponse('author');
        }
        if (!$this->request->has('category')) {
            return $this->missingFieldResponse('category');
        }

        $book = $this->bookService->putBook($this->request->user_id, $book_id, $this->request->all());
        if (!is_null($book)) {
            return $this->successResponse(200, 'Book succesfully updated', $book);
        }
        return $this->errorResponse(404, 'No book found with that ID');
    }

    public function removeBook(int $book_id, int $user_id)
    {
        if ($this->bookService->deleteBook($book_id, $user_id)) {
            return $this->successResponse(200, 'Book succesfully deleted');
        }
        return $this->errorResponse(404, 'No book found with that ID');
    }
}
