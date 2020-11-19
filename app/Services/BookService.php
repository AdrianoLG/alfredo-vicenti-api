<?php

namespace App\Services;

use App\Models\Book;
use App\Models\User;

class BookService
{
    private $model;

    public function __construct()
    {
        $this->model = new Book();
    }

    public function getBooks(int $user_id)
    {
        $user = User::find($user_id);
        return $user->booksWithFields;
    }

    public function getBook(int $book_id, int $user_id)
    {
        $user = User::find($user_id);
        return $user->books->find($book_id);
    }

    public function postBook(array $book)
    {
        if (!is_null($book)) {
            $newBook = $this->model->create($book);
            return $newBook;
        } else {
            return null;
        }
    }

    public function putBook(int $user_id, int $book_id, array $book)
    {
        $user = User::find($user_id);
        $bookToUpdate = $user->books->find($book_id);

        if (!is_null($bookToUpdate)) {
            $bookToUpdate->update($book);
            return $bookToUpdate;
        }
        return null;
    }

    public function deleteBook(int $book_id, int $user_id)
    {
        $user = User::find($user_id);
        $book = $user->books->find($book_id);

        if (!is_null($book)) {
            $book->delete();
            return true;
        }
        return false;
    }
}
