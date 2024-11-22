<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KategoriBarangController extends Controller
{
    public function index()
    {
        return view('master.barang.kategori');
    }

    public function create()
    {
    }

    public function store(Request $req)
    {
    }

    public function show(string $id)
    {
    }

    public function edit(string $id)
    {
    }

    public function update(Request $req, string $id)
    {
    }

    public function destroy(string $id)
    {
    }
}
