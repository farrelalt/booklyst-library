<?php

namespace App\Jobs;

use App\Models\Loan;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessLoanRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;    // retry 3x kalau gagal
    public int $timeout = 60; // timeout 60 detik

    public function __construct(
        public int $memberId,
        public int $bookId,
        public string $loanDate
    ) {}

    public function handle(): void
    {
        $client = new Client(['http_errors' => false, 'timeout' => 10]);

        Log::info("[Queue] Mulai proses loan: member={$this->memberId}, book={$this->bookId}");

        // 1. Cek member ke user-service
        try {
            $memberResp = $client->get(env('USER_SERVICE_URL') . "/api/members/{$this->memberId}");
            if ($memberResp->getStatusCode() !== 200) {
                Log::error("[Queue] Member {$this->memberId} tidak ditemukan");
                return;
            }
        } catch (\Exception $e) {
            Log::error("[Queue] Gagal koneksi ke user-service: " . $e->getMessage());
            $this->fail($e);
            return;
        }

        // 2. Cek stok buku ke book-service
        try {
            $bookResp = $client->get(env('BOOK_SERVICE_URL') . "/api/books/{$this->bookId}");
            if ($bookResp->getStatusCode() !== 200) {
                Log::error("[Queue] Buku {$this->bookId} tidak ditemukan");
                return;
            }
            $bookData = json_decode($bookResp->getBody(), true)['data'] ?? null;
            if (!$bookData || ($bookData['stock'] ?? 0) <= 0) {
                Log::warning("[Queue] Stok buku {$this->bookId} habis");
                return;
            }
        } catch (\Exception $e) {
            Log::error("[Queue] Gagal koneksi ke book-service: " . $e->getMessage());
            $this->fail($e);
            return;
        }

        // 3. Kurangi stok
        $client->patch(
            env('BOOK_SERVICE_URL') . "/api/books/{$this->bookId}/stock",
            ['json' => ['action' => 'decrement']]
        );

        // 4. Simpan loan ke database
        $loan = Loan::create([
            'member_id'   => $this->memberId,
            'book_id'     => $this->bookId,
            'loan_date'   => $this->loanDate,
            'return_date' => null,
            'status'      => 'borrowed',
        ]);

        Log::info("[Queue] Loan berhasil dibuat: id={$loan->id}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("[Queue] Job gagal total: " . $exception->getMessage());
    }
}