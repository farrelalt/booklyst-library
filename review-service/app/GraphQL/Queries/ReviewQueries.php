<?php

namespace App\GraphQL\Queries;

use App\Models\Review;

class ReviewQueries
{
    public function all(mixed $root, array $args): \Illuminate\Database\Eloquent\Collection
    {
        return Review::all();
    }

    public function find(mixed $root, array $args): ?Review
    {
        return Review::find($args['id']);
    }

    public function byBook(mixed $root, array $args): \Illuminate\Database\Eloquent\Collection
    {
        return Review::where('book_id', $args['book_id'])->get();
    }

    public function byMember(mixed $root, array $args): \Illuminate\Database\Eloquent\Collection
    {
        return Review::where('member_id', $args['member_id'])->get();
    }
}