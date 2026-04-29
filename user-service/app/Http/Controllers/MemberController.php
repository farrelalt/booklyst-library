<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    // GET /api/members — ambil semua member
    public function index()
    {
        $members = Member::all();
        return response()->json([
            'status' => 'success',
            'service' => 'UserService',
            'data' => $members
        ]);
    }

    // GET /api/members/{id} — ambil member by ID
    public function show($id)
    {
        $member = Member::find($id);
        if (!$member) {
            return response()->json(['status' => 'error', 'message' => 'Member not found'], 404);
        }
        return response()->json([
            'status' => 'success',
            'service' => 'UserService',
            'data' => $member
        ]);
    }

    // POST /api/members — tambah member
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:members,email',
            'phone' => 'nullable|string',
        ]);

        $member = Member::create($validated);
        return response()->json([
            'status' => 'success',
            'service' => 'UserService',
            'data' => $member
        ], 201);
    }

    // GET /api/members/{id}/loans — consumer: ambil riwayat peminjaman dari LoanService
    public function memberLoans($id)
    {
        $member = Member::find($id);
        if (!$member) {
            return response()->json(['status' => 'error', 'message' => 'Member not found'], 404);
        }

        // HTTP request langsung ke LoanService (consumer)
        $client = new \GuzzleHttp\Client();
        $response = $client->get("http://localhost:8003/api/loans/member/{$id}");
        $loans = json_decode($response->getBody(), true);

        return response()->json([
            'status' => 'success',
            'service' => 'UserService (consumer of LoanService)',
            'member' => $member,
            'loans' => $loans['data'] ?? []
        ]);
    }
}