<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\KategoriBarang;
use App\Http\Libraries\Tools;
use App\Http\Libraries\ResponseCode;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\DB;

class KategoriBarangController extends Controller
{
    private function checkValidation($req){
        $req->validate([
            'name' => ['required', 'string', 'max:255', 'unique:'.KategoriBarang::class],
        ]);
    }

    private $resCode, $tool, $kategori_barang, $userAgent;
    public function __construct()
    {
        $this->tool = new Tools;
        $this->resCode = new ResponseCode;
        $this->kategori_barang = new KategoriBarang;
        $this->userAgent = request()->header('User-Agent');
    }

    public function index()
    {
        try {
            $getDatas = $this->tool->isValidVal($this->kategori_barang::all());
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
    public function create()
    {
    }

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

            $dataKatBarang = DB::table('kategori_barang')
            ->select('id')
            ->whereRaw('LOWER(name) = LOWER(?)', [$req->name])
            ->first();

            if (isset($dataKatBarang) && !empty($dataKatBarang)) {
                throw new \Exception("data sudah digunakan!");
            }

            $kategoriBarang = KategoriBarang::create([
                'name' => $req->name,
                'description' => $req->description,
            ]);

            return $this->resCode->CREATED("berhasil menyimpan data", $kategoriBarang);
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
            $getDatas = $this->tool->isValidVal($this->kategori_barang::find($id));
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
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        setlocale(LC_TIME, 'id_ID.utf8');

        try {
            $kategoriBarang = KategoriBarang::findOrFail($id);

            if (strcasecmp($kategoriBarang->name, $req->name) !== 0) {
                $kategoriBarang->name = $req->name;
            }

            $kategoriBarang->description = (isset($req->description) && !empty($req->description) ? $req->description : '');

            $callback = $kategoriBarang->save();

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
            $getDatas = $this->tool->isValidVal($this->kategori_barang::find($id));
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
