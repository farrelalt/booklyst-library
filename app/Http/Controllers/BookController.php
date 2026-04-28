<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return response()->json([
            'status'  => 'success',
            'service' => 'BookService',
            'data'    => Book::all()
        ]);
    }

    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'service' => 'BookService',
            'data'    => $book
        ]);
    }

    public function store(Request $request)
    {
        $book = Book::create($request->only(['title', 'author', 'isbn', 'stock']));

        return response()->json([
            'status'  => 'success',
            'service' => 'BookService',
            'data'    => $book
        ], 201);
    }

    public function bookBorrowers($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->get("http://localhost:8003/api/loans/book/{$id}");
        $loans = json_decode($response->getBody(), true);

        return response()->json([
            'status'  => 'success',
            'service' => 'BookService (consumer of LoanService)',
            'book'    => $book,
            'loans'   => $loans['data'] ?? []
        ]);
    }
}
