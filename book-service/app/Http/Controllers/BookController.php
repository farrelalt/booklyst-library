<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'service' => 'BookService',
            'data' => Book::all()
        ]);
    }

    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'service' => 'BookService',
            'data' => $book
        ]);
    }
    public function updateStock(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        $action = $request->input('action');

        if ($action === 'decrement') {
            if ($book->stock <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stock habis'
                ], 422);
            }
            $book->stock -= 1;
        }

        if ($action === 'increment') {
            $book->stock += 1;
        }

        $book->save();

        return response()->json([
            'status' => 'success',
            'service' => 'BookService',
            'data' => $book
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'isbn' => 'required|string|unique:books,isbn',
            'stock' => 'nullable|integer|min:1',
        ]);

        $book = Book::create($validated);
        return response()->json([
            'status' => 'success',
            'service' => 'BookService',
            'data' => $book
        ], 201);
    }

    public function bookBorrowers($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->get("http://localhost:8003/api/loans/book/{$id}");
        $loans = json_decode($response->getBody(), true);

        return response()->json([
            'status' => 'success',
            'service' => 'BookService (consumer of LoanService)',
            'book' => $book,
            'loans' => $loans['data'] ?? []
        ]);
    }
}
