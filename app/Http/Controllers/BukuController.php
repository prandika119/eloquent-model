<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $data_buku = Buku::all()->sortByDesc('id');
        return view('buku.index', compact('data_buku'));
    }
}
