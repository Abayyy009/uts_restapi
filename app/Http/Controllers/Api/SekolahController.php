<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah; 

class SekolahController extends Controller
{
    public function index()
    {
        $sekolah = Sekolah::paginate(10);
        return response()->json([
            'data' => $sekolah
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'email' => 'required|email',
            'jenis_sekolah' => 'required',
            'status_sekolah' => 'required',
            'akreditasi' => 'required',
        ]);

        $sekolah = Sekolah::create($request->all());

        return response()->json($sekolah, 201);
    }

    public function show(Sekolah $sekolah)
    {
        return $sekolah;
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'email' => 'required|email',
            'jenis_sekolah' => 'required',
            'status_sekolah' => 'required',
            'akreditasi' => 'required',
        ]);
        $sekolah->update($request->all());

        return response()->json($sekolah);
    }

    public function destroy(Sekolah $sekolah)
    {
        $sekolah->delete();

        return response()->json(null, 204);
    }
}