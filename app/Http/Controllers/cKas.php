<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kas;
use App\Models\kas_kategori;
use App\Models\pemasukan;
use App\Models\pengeluaran;
use App\Models\penyusutan;

class cKas extends Controller
{
    public function kas()
    {
        $get = kas::get();
        return view('Kas.tables', [
            'data' => $get,
        ]);
    }
    public function KasKategori()
    {
        $get = kas_kategori::get();
        return view('Kas_Kategori.tables', [
            'data' => $get,
        ]);
    }
    public function KasKategoriview($id)
    {
        $namakas = kas_kategori::where('id', $id)->get()->first();
        $pos = strpos($namakas->nama, " ");
        $first_part = substr($namakas->nama, 0, $pos);
        $sec_part = substr($namakas->nama, $pos+1, -7);
        $third_part = substr($namakas->nama, -7);
        if ($first_part == 'Pemasukan') {
            $get = pemasukan::where('status', 'lunas')->where('nama_produk', $sec_part)->where('tanggal', 'like', $third_part . "%")->get();
            return view('Pemasukan.tables', [
                'data' => $get,
            ]);
        } else if ($first_part == 'Penyusutan') {
            $get = penyusutan::where('nama_produk', $sec_part)->where('tanggal', 'like', $third_part . "%")->get();
            return view('Penyusutan.tables', [
                'data' => $get,
            ]);
        } else if ($first_part == 'Pengeluaran') {
            $get = pengeluaran::where(function ($query) use ($sec_part) {
                $query->where('kategori', $sec_part)
                    ->orWhere('supplier', $sec_part);
            })->where('status', 'lunas')->where('tanggal', 'like', $third_part . "%")->get();
            return view('Pengeluaran.tables', [
                'data' => $get,
            ]);
        }
    }
}
