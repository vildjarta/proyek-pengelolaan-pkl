<?php

namespace App\Http\Controllers;

use App\Models\DataDosenPembimbing;
use Illuminate\Http\Request;

class DataDosenPembimbingController extends Controller
{
    public function index()
    {
        $data = DataDosenPembimbing::all();
        // sesuai folder dan nama file kamu sekarang
        return view('datadosenpembimbing.datadosenpembimbing', compact('data'));
    }

    public function create()
    {
        // sesuai folder dan nama file kamu sekarang
        return view('datadosenpembimbing.tambahdatadosenpembimbing');
    }

    public function store(Request $request)
    {
        $request->validate([
            'NIP'   => 'required|unique:dosen_pembimbing,NIP',
            'nama'  => 'required',
            'email' => 'required|email|unique:dosen_pembimbing,email'
        ]);

        DataDosenPembimbing::create($request->all());

        // gunakan nama route sesuai resource di web.php
        return redirect()->route('datadosenpembimbing.index')
                         ->with('success','Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $item = DataDosenPembimbing::findOrFail($id);
        // sesuai folder dan nama file kamu sekarang
        return view('datadosenpembimbing.editdatadosenpembimbing', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = DataDosenPembimbing::findOrFail($id);

        $request->validate([
            'nama'  => 'required',
            // abaikan email yang sekarang dipakai oleh id ini
            'email' => 'required|email|unique:dosen_pembimbing,email,' . $id . ',id_pembimbing'
        ]);

        $item->update($request->all());

        return redirect()->route('datadosenpembimbing.index')
                         ->with('success','Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $item = DataDosenPembimbing::findOrFail($id);
        $item->delete();

        return redirect()->route('datadosenpembimbing.index')
                         ->with('success','Data berhasil dihapus');
    }
}
