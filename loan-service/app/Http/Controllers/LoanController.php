<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Jobs\ProcessLoanRequest;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller
{
    public function index()
    {
        return response()->json([
            'status'  => 'success',
            'service' => 'LoanService',
            'data'    => Loan::latest()->get()
        ]);
    }

    public function byMember($memberId)
    {
        return response()->json([
            'status' => 'success',
            'service' => 'LoanService',
            'data'   => Loan::where('member_id', $memberId)->get()
        ]);
    }

    public function byBook($bookId)
    {
        return response()->json([
            'status' => 'success',
            'service' => 'LoanService',
            'data'   => Loan::where('book_id', $bookId)->latest()->get()
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
        $request->validate([
            'member_id' => 'required|integer',
            'book_id'   => 'required|integer',
            'loan_date' => 'nullable|date',
        ]);

        // Lempar job ke Redis, tidak tunggu selesai
        ProcessLoanRequest::dispatch(
            $request->member_id,
            $request->book_id,
            $request->loan_date ?? now()->toDateString()
        );

        // Langsung balas 202 (Accepted) ke client
        return response()->json([
            'status'  => 'queued',
            'service' => 'LoanService',
            'message' => 'Permintaan peminjaman sedang diproses secara asinkron',
        ], 202);
    }

    public function returnBook($id)
    {
        $loan = Loan::find($id);
        if (!$loan) {
            return response()->json(['status' => 'error', 'message' => 'Loan not found'], 404);
        }
        if ($loan->status === 'returned') {
            return response()->json(['status' => 'error', 'message' => 'Buku sudah dikembalikan'], 422);
        }

        $loan->update(['status' => 'returned', 'return_date' => now()->toDateString()]);

        // Tambah stok kembali (tetap sinkron, karena harus konfirmasi)
        try {
            $client = new Client(['http_errors' => false, 'timeout' => 10]);
            $client->patch(
                env('BOOK_SERVICE_URL', 'http://book-service:8000') . "/api/books/{$loan->book_id}/stock",
                ['json' => ['action' => 'increment']]
            );
        } catch (\Exception $e) {
            Log::warning("Gagal increment stok: " . $e->getMessage());
        }

        return response()->json([
            'status'  => 'success',
            'service' => 'LoanService',
            'data'    => $loan->fresh()
        ]);
    }
}