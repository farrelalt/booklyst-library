<?php

namespace App\GraphQL\Mutations;

use App\Models\Loan;
use GuzzleHttp\Client;

class LoanMutation
{
    public function createLoan($_, array $args)
    {
        $client = new Client([
            'http_errors' => false,
            'timeout' => 5,
        ]);

        // ambil base URL dari config (WAJIB)
        $userUrl = rtrim(config('services.user_service.url'), '/');
        $bookUrl = rtrim(config('services.book_service.url'), '/');

        if (!$userUrl || !$bookUrl) {
            throw new \Exception("Service URL belum dikonfigurasi");
        }

        // CHECK USER SERVICE
        $memberResp = $client->get(
            "{$userUrl}/api/users/{$args['member_id']}"
        );

        if ($memberResp->getStatusCode() !== 200) {
            throw new \Exception("User tidak ditemukan");
        }

        // CHECK BOOK SERVICE
        $bookResp = $client->get(
            "{$bookUrl}/api/books/{$args['book_id']}"
        );

        if ($bookResp->getStatusCode() !== 200) {
            throw new \Exception("Book tidak ditemukan");
        }


        // CREATE LOAN
        $loan = Loan::create([
            'member_id' => $args['member_id'],
            'book_id'   => $args['book_id'],
            'loan_date' => $args['loan_date'] ?? now(),
            'status'    => 'borrowed',
        ]);

        return $loan;
    }
}
