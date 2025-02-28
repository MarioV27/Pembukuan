<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\kas;
use App\Models\kas_kategori;

class cKas extends Controller
{
    public function kas() {
        $get = kas::get();
        return view('Kas.tables', [
            'data' => $get,
        ]);
    }
    public function KasKategori() {
        $get = kas_kategori::get();
        return view('Kas_Kategori.tables', [
            'data' => $get,
        ]);
    }
    
}
