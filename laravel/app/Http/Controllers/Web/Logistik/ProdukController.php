<?php

namespace App\Http\Controllers\Web\Logistik;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProdukController extends Controller
{
    public function index()
    {
        return view('logistik.barang.produk');
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
