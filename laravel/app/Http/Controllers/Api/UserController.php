<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

use App\Models\User;
use App\Models\Role;
use App\Models\Pegawai;
use App\Models\Penduduk;
use App\Http\Libraries\Tools;
use App\Http\Libraries\ResponseCode;

class UserController extends Controller
{
    private function checkValidation($req){
        $req->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    }

    private $resCode, $tool, $user, $role, $userAgent;
    public function __construct()
    {
        $this->tool = new Tools;
        $this->resCode = new ResponseCode;
        $this->user = new User;
        $this->userAgent = request()->header('User-Agent');

        $tmpRole = new Role;
        $this->role = $tmpRole->all();
    }

    public function index()
    {
        try {
            $getDatas = $this->tool->isValidVal($this->user::all());
            if ($getDatas) {
                return $this->resCode->OKE("berhasil mengambil data", $getDatas);
            }
            return $this->resCode->OKE("tidak ada data");
        } catch (Exception $th) {
            return $this->resCode->SERVER_ERROR("kesalahan dalam mengambil data!", $th);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
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

            DB::beginTransaction();

            $penduduk = Penduduk::create([
                'fullname' => $req->name
            ]);

            $user = User::create([
                'username' => $req->username,
                'email' => $req->email,
                'password' => Hash::make($req->password)
            ]);

            $user->expired_date = now('Asia/Jakarta')->addDays(30)->toDateTimeString();
            $user->save();

            Pegawai::create([
                'id_user' => $user->id,
                'id_penduduk' => $penduduk->id
            ]);

            DB::commit();

            event(new Registered($user));
            Auth::login($user);

            return $this->resCode->CREATED("berhasil menyimpan data", $user);
        } catch (ValidationException $th) {
            DB::rollBack();
            return $this->resCode->SERVER_ERROR("kesalahan dalam menyimpan data!", $th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $getDatas = $this->tool->isValidVal($this->user::find($id));
            if ($getDatas) {
                return $this->resCode->OKE("berhasil mengambil data", $getDatas);
            }
            return $this->resCode->OKE("tidak ada data");
        } catch (Exception $th) {
            return $this->resCode->SERVER_ERROR("kesalahan dalam mengambil data!", $th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
