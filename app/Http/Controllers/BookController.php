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
        if (is_null($this->request->user_id)) {
            return $this->missingFieldResponse('user_id');
        }
        if (is_null($this->request->title)) {
            return $this->missingFieldResponse('title');
        }
        if (is_null($this->request->author)) {
            return $this->missingFieldResponse('author');
        }
        if (is_null($this->request->category)) {
            return $this->missingFieldResponse('category');
        }

        $this->bookService->postBook($this->request->all());

        return $this->successResponse(201, 'Book succesfully created');
    }

    public function getBooks()
    {
        $books = $this->bookService->getBooks($this->request->user_id);

        return $this->successResponse(200, null, $books);
    }

    public function updateBook(int $id)
    {
        if (is_null($this->request->user_id)) {
            return $this->missingFieldResponse('user_id');
        }
        if (is_null($this->request->title)) {
            return $this->missingFieldResponse('title');
        }
        if (is_null($this->request->author)) {
            return $this->missingFieldResponse('author');
        }
        if (is_null($this->request->category)) {
            return $this->missingFieldResponse('category');
        }

        if ($this->bookService->putBook($this->request->user_id, $id, $this->request->all())) {
            return $this->successResponse(200, 'Book succesfully updated');
        }
        return $this->errorResponse(404, 'No book found with that ID');
    }

    public function removeBook(int $id)
    {
        if (is_null($this->request->user_id)) {
            return $this->missingFieldResponse('user_id');
        }
        $user_id = $this->request->user_id;

        if ($this->bookService->deleteBook($user_id, $id)) {
            return $this->successResponse(200, 'Book succesfully deleted');
        }
        return $this->errorResponse(404, 'No book found with that ID');
    }
}
