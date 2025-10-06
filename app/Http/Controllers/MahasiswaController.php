<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Mengecek apakah NIM ada di database (dipakai untuk AJAX)
     */
public function cekNim($nim)
{
    $mahasiswa = Mahasiswa::where('nim', $nim)->first();
    if ($mahasiswa) {
        return response()->json([
            'exists' => true,
            'nama_mahasiswa' => $mahasiswa->nama
        ]);
    } else {
        return response()->json([
            'exists' => false
        ]);
    }
}
}