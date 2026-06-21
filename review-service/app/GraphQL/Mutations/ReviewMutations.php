<?php

namespace App\GraphQL\Mutations;

use App\Models\Review;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class ReviewMutations
{
    public function create(mixed $root, array $args): Review
    {
        $memberId = $args['member_id'];
        $bookId   = $args['book_id'];

        // Validasi member ke user-service
        $memberRes = Http::get(env('USER_SERVICE_URL') . "/api/members/{$memberId}");
        if (!$memberRes->successful()) {
            throw new \Exception("Member dengan ID {$memberId} tidak ditemukan.");
        }

        // Validasi buku ke book-service
        $bookRes = Http::get(env('BOOK_SERVICE_URL') . "/api/books/{$bookId}");
        if (!$bookRes->successful()) {
            throw new \Exception("Buku dengan ID {$bookId} tidak ditemukan.");
        }

        // Validasi member pernah meminjam buku ini
        $loanRes = Http::get(env('LOAN_SERVICE_URL') . "/api/loans/member/{$memberId}");
        if ($loanRes->successful()) {
            $loans  = collect($loanRes->json('data') ?? []);
            $pernah = $loans->contains(fn($loan) => $loan['book_id'] == $bookId);
            if (!$pernah) {
                throw new \Exception("Member belum pernah meminjam buku ini.");
            }
        }

        // Validasi member belum pernah review buku ini
        $sudahReview = Review::where('member_id', $memberId)
            ->where('book_id', $bookId)
            ->exists();
        if ($sudahReview) {
            throw new \Exception("Anda sudah pernah memberikan review untuk buku ini.");
        }

        // Simpan review
        $review = Review::create([
            'member_id' => $memberId,
            'book_id'   => $bookId,
            'rating'    => $args['rating'],
            'comment'   => $args['comment'],
        ]);

        // Dispatch event ke Redis queue
        Queue::push(function () use ($review) {
            Log::info('Review baru masuk', [
                'review_id' => $review->id,
                'member_id' => $review->member_id,
                'book_id'   => $review->book_id,
                'rating'    => $review->rating,
            ]);
        });

        return $review;
    }

    public function delete(mixed $root, array $args): ?Review
    {
        $review = Review::find($args['id']);
        if (!$review) {
            throw new \Exception("Review tidak ditemukan.");
        }

        $review->delete();

        // Dispatch event ke Redis queue
        Queue::push(function () use ($review) {
            Log::info('Review dihapus', [
                'review_id' => $review->id,
                'member_id' => $review->member_id,
                'book_id'   => $review->book_id,
            ]);
        });

        return $review;
    }
}