<?php

namespace App\GraphQL\Mutations;

use App\Models\Loan;
use App\Services\ExternalService;

class LoanMutation
{
    protected $service;

    public function __construct(ExternalService $service)
    {
        $this->service = $service;
    }

    // ================= CREATE LOAN =================
    public function createLoan($_, array $args)
    {
        $user = $this->service->getUser($args['user_id']);
        $book = $this->service->getBook($args['book_id']);

        if (!$user || !$book) {
            throw new \Exception("User atau Book tidak ditemukan");
        }

        // simpan loan
        $loan = Loan::create([
            'user_id' => $args['user_id'],
            'book_id' => $args['book_id'],
            'loan_date' => now(),
            'return_date' => null,
            'status' => 'borrowed'
        ]);

        // update status book ke borrowed
        $this->service->updateBookStatus($args['book_id'], 'borrowed');

        return $loan;
    }

    // ================= UPDATE LOAN =================
    public function updateLoan($_, array $args)
    {
        $loan = Loan::find($args['id']);

        if (!$loan) {
            throw new \Exception("Loan tidak ditemukan");
        }

        $loan->update([
            'return_date' => $args['return_date'] ?? $loan->return_date,
            'status' => $args['status'] ?? $loan->status,
        ]);

        // kalau dikembalikan → update book status
        if (($args['status'] ?? null) === 'returned') {
            $this->service->updateBookStatus($loan->book_id, 'available');
        }

        return $loan;
    }
}
