<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Produk;
use App\Http\Libraries\Tools;
use App\Http\Libraries\ResponseCode;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    private function checkValidation($req)
    {
        $req->validate([
            'name' => ['required', 'string', 'max:255', 'unique:' . Produk::class],
        ]);
    }

    private $resCode, $tool, $produk, $userAgent, $strNull;
    public function __construct()
    {
        $this->tool = new Tools;
        $this->resCode = new ResponseCode;
        $this->produk = new Produk;
        $this->userAgent = request()->header('User-Agent');
        $this->strNull = ["null", "NULL", "Null"];
    }

    public function index()
    {
        try {
            $tmpData = DB::select("SELECT prdk.id, prdk.name, prdk.description, prdk.harga_beli, prdk.harga_jual, prdk.stok, kbar.name AS kategori_barang FROM produk prdk
            LEFT JOIN kategori_barang kbar ON kbar.id = prdk.id_kategori_barang");
            $getDatas = $this->tool->isValidVal($tmpData);
            if ($getDatas) {
                return $this->resCode->OKE("berhasil mengambil data", $getDatas);
            }
            return $this->resCode->OKE("tidak ada data");
        } catch (\Throwable $th) {
            return $this->resCode->SERVER_ERROR("kesalahan dalam mengambil data!", $th);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        setlocale(LC_TIME, 'id_ID.utf8');

        if (strpos($this->userAgent, 'Mozilla') !== false) {
            $this->checkValidation($req);
        }

        try {
            if (strpos($this->userAgent, 'Postman') !== false) {
                $this->checkValidation($req);
            }

            $dataProduk = DB::table('produk')
                ->select('id')
                ->whereRaw('LOWER(name) = LOWER(?)', [$req->name])
                ->first();

            if (isset($dataProduk) && !empty($dataProduk)) {
                throw new \Exception("data sudah digunakan!");
            }

            $produk = Produk::create([
                'name' => $req->name,
                'description' => $req->description,
                'id_kategori_barang' => $req->id_kategori_barang,
                'harga_beli' => $req->harga_beli,
                'harga_jual' => $req->harga_jual,
                'stok' => $req->stok,
            ]);

            return $this->resCode->CREATED("berhasil menyimpan data", $produk);
        } catch (ValidationException $th) {
            return $this->resCode->SERVER_ERROR("kesalahan dalam menyimpan data!", $th);
        } catch (\Exception $th) {
            $tmp = $th->getMessage();
            return $this->resCode->FORBIDDEN("gagal dalam menyimpan data!\n$tmp");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $getDatas = $this->tool->isValidVal($this->produk::find($id));
            if ($getDatas) {
                return $this->resCode->OKE("berhasil mengambil data", $getDatas);
            }
            return $this->resCode->OKE("tidak ada data");
        } catch (\Throwable $th) {
            return $this->resCode->SERVER_ERROR("kesalahan dalam mengambil data!", $th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        setlocale(LC_TIME, 'id_ID.utf8');

        try {
            $produk = Produk::findOrFail($id);

            if (strcasecmp($produk->name, $req->name) !== 0) {
                $produk->name = $req->name;
            }

            $produk->description = (isset($req->description) && !empty($req->description) && !(in_array($req->description, $this->strNull)) ? $req->description : null);
            $produk->id_kategori_barang = (isset($req->id_kategori_barang) && !empty($req->id_kategori_barang) ? $req->id_kategori_barang : null);
            $produk->harga_beli = (isset($req->harga_beli) && !empty($req->harga_beli) ? $req->harga_beli : null);
            $produk->harga_jual = (isset($req->harga_jual) && !empty($req->harga_jual) ? $req->harga_jual : null);
            $produk->stok = (isset($req->stok) && !empty($req->stok) ? $req->stok : null);

            $callback = $produk->save();

            if (empty($callback)) {
                throw new \Exception("data sudah digunakan!");
            }

            return $this->resCode->CREATED("berhasil menyimpan data");
        } catch (ValidationException $th) {
            return $this->resCode->SERVER_ERROR("kesalahan dalam menyimpan data!", $th);
        } catch (\Exception $th) {
            $tmp = $th->getMessage();
            return $this->resCode->FORBIDDEN("gagal dalam menyimpan data!\ndata sudah terpakai.", $tmp);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        setlocale(LC_TIME, 'id_ID.utf8');

        try {
            $getDatas = $this->tool->isValidVal($this->produk::find($id));
            if (empty($getDatas)) {
                return $this->resCode->OKE("tidak ada data");
            }

            $getDatas->delete();

            return $this->resCode->CREATED("berhasil menghapus data", $getDatas);
        } catch (ValidationException $th) {
            return $this->resCode->SERVER_ERROR("kesalahan dalam menghapus data!", $th);
        }
    }
}
