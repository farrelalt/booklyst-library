<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(['http_errors' => false]);
    }

    // Halaman utama — tampilkan semua data
    public function index()
    {
        $members = $this->fetch('http://localhost:8001/api/members');
        $books = $this->fetch('http://localhost:8002/api/books');
        $loans = $this->fetch('http://localhost:8003/api/loans');

        return view('dashboard', compact('members', 'books', 'loans'));
    }

    // Form tambah member
    public function storeMember(Request $request)
    {
        $this->client->post('http://localhost:8001/api/members', [
            'json' => $request->only(['name', 'email', 'phone'])
        ]);
        return redirect('/')->with('success', 'Member berhasil ditambahkan!');
    }

    // Form tambah buku
    public function storeBook(Request $request)
    {
        $this->client->post('http://localhost:8002/api/books', [
            'json' => $request->only(['title', 'author', 'isbn', 'stock'])
        ]);
        return redirect('/')->with('success', 'Buku berhasil ditambahkan!');
    }

    // Form tambah peminjaman
    public function storeLoan(Request $request)
    {
        $response = $this->client->post('http://localhost:8003/api/loans', [
            'json' => $request->only(['member_id', 'book_id', 'loan_date'])
        ]);
        $result = json_decode($response->getBody(), true);
        if (isset($result['status']) && $result['status'] === 'error') {
            return redirect('/')->with('error', $result['message']);
        }
        return redirect('/')->with('success', 'Peminjaman berhasil dibuat!');
    }

    // Kembalikan buku
    public function returnLoan($id)
    {
        $this->client->put("http://localhost:8003/api/loans/{$id}/return");
        return redirect('/')->with('success', 'Buku berhasil dikembalikan!');
    }

    private function fetch($url)
    {
        try {
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);
            return $data['data'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }
}