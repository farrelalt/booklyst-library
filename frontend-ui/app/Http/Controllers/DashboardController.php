<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $client;
    private string $userUrl;
    private string $bookUrl;
    private string $loanUrl;

    public function __construct()
    {
        $this->client  = new \GuzzleHttp\Client(['http_errors' => false, 'timeout' => 10]);
        $this->userUrl = env('USER_SERVICE_URL', 'http://localhost:8001');
        $this->bookUrl = env('BOOK_SERVICE_URL', 'http://localhost:8002');
        $this->loanUrl = env('LOAN_SERVICE_URL', 'http://localhost:8003');
    }

    public function index()
    {
        $members = $this->fetch("{$this->userUrl}/api/members");
        $books   = $this->fetch("{$this->bookUrl}/api/books");
        $loans   = $this->fetch("{$this->loanUrl}/api/loans");
        return view('dashboard', compact('members', 'books', 'loans'));
    }

    public function storeMember(Request $request)
    {
        $this->client->post("{$this->userUrl}/api/members", [
            'json' => $request->only(['name', 'email', 'phone'])
        ]);
        return redirect('/')->with('success', 'Member berhasil ditambahkan!');
    }

    public function storeBook(Request $request)
    {
        $this->client->post("{$this->bookUrl}/api/books", [
            'json' => $request->only(['title', 'author', 'isbn', 'stock'])
        ]);
        return redirect('/')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function storeLoan(Request $request)
    {
        $response = $this->client->post("{$this->loanUrl}/api/loans", [
            'json' => $request->only(['member_id', 'book_id', 'loan_date'])
        ]);
        $result = json_decode($response->getBody(), true);

        // status 'queued' berarti berhasil masuk antrian
        if (in_array($result['status'] ?? '', ['success', 'queued'])) {
            return redirect('/')->with('success', 'Peminjaman diproses! Refresh sebentar lagi.');
        }
        return redirect('/')->with('error', $result['message'] ?? 'Terjadi kesalahan');
    }

    public function returnLoan($id)
    {
        $this->client->put("{$this->loanUrl}/api/loans/{$id}/return");
        return redirect('/')->with('success', 'Buku berhasil dikembalikan!');
    }

    private function fetch(string $url): array
    {
        try {
            $response = $this->client->get($url);
            return json_decode($response->getBody(), true)['data'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }
}