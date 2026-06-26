<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use GuzzleHttp\Client;

class LoanController extends Controller
{
    //GET/api-loan / - semua peminjaman
    public function index()
    {
        $loans = Loan::latest()->get();

        return response()->json
        ([
            'status'  => 'success',
            'service' => 'LoanService',
            'data'    => Loan::all()
        ]);
    }

    //GET/api-loan/member - peminjaman by member
    public function byMember($memberId)
    {
        $loans = Loan::where('member_id', $memberId)->get();
        return response()->json
        ([
            'status'  => 'success',
            'service' => 'LoanService',
            'data'    => $loans
        ]);
    }

    //GET/api-loan/book - peminjaman by buku
    public function byBook($bookId)
    {
        $loans = Loan::where('book_id', $bookId)->latest()->get();

        return response()->json([
            'status'  => 'success',
            'service' => 'LoanService',
            'data'    => $loans
        ]);
    }

    public function show($id)
    {
        $loan = Loan::findOrFail($id);

        return response()->json($loan);
    }

    //POST/api-loan - buat peminjaman baru
    public function store(Request $request)
    {
        $client = new \GuzzleHttp\Client(['http_errors' => false]);

        // 1. Validasi input
        $request->validate
        ([
            'member_id' => 'required|integer',
            'book_id' => 'required|integer',
            'loan_date' => 'nullable|date'
        ]);

        // 2. Validasi Member (UserService)
        try {
            $memberResp = $client->get("http://localhost:8001/api/members/{$request->member_id}");
        } catch (\Exception $e) {
            return response()->json
            ([
                'status' => 'error',
                'message' => 'UserService tidak aktif'
            ], 500);
        }

        if ($memberResp->getStatusCode() !== 200) {
            return response()->json
            ([
                'status' => 'error',
                'message' => 'Member not found in UserService'
            ], 404);
        }

        $memberJson = json_decode($memberResp->getBody(), true);

        if (!$memberJson || !isset($memberJson['data'])) {
            return response()->json
            ([
                'status' => 'error',
                'message' => 'Invalid response from UserService'
            ], 500);
        }

        $memberData = $memberJson['data'];

        // 3. Validasi Book (BookService)
        try {
            $bookResp = $client->get("http://localhost:8002/api/books/{$request->book_id}");
        } catch (\Exception $e) {
            return response()->json
            ([
                'status' => 'error',
                'message' => 'BookService tidak aktif'
            ], 500);
        }

        if ($bookResp->getStatusCode() !== 200) {
            return response()->json
            ([
                'status' => 'error',
                'message' => 'Book not found in BookService'
            ], 404);
        }

        $bookJson = json_decode($bookResp->getBody(), true);

        if (!$bookJson || !isset($bookJson['data'])) {
            return response()->json
            ([
                'status' => 'error',
                'message' => 'Invalid response from BookService'
            ], 500);
        }

        $bookData = $bookJson['data'];

        // 4. Simpan ke database
        $loan = Loan::create
        ([
            'member_id'   => $request->member_id,
            'book_id'     => $request->book_id,
            'loan_date'   => $request->loan_date ?? now()->toDateString(),
            'return_date' => null,
            'status'      => 'borrowed'
        ]);

        // 5. Response
        return response()->json
        ([
            'status'  => 'success',
            'service' => 'LoanService',
            'loan'    => $loan,
            'member'  => $memberData,
            'book'    => $bookData
        ], 201);
    }
}
