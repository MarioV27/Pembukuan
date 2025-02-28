<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\penyusutan;
use App\Models\kas;
use App\Models\kategori;
use App\Models\kas_kategori;
use Illuminate\Support\Facades\DB;

class cPenyusutan extends Controller
{
    public function penyusutan()
    {
        $get = penyusutan::get();
        return view('Penyusutan.tables', [
            'data' => $get,
        ]);
    }
    public function view_form()
    {
        $get = kategori::where('kategori', 'Penyusutan')->get();
        return view('Penyusutan.forms', [
            'title' => 'Dashboard-SIARAN',
            'data' => $get
        ]);
    }
    public function view_edit($id)
    {
        $gets = kategori::where('kategori', 'Penyusutan')->get();
        $get = penyusutan::where('id', $id)->get()->first();
        return view('Penyusutan.edit', [
            'data' => $get,
            'datas' => $gets
        ]);
    }
    public function post_form(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required',
            'nama_produk' => 'required',
            'jumlah' => 'required',
            'harga_jual' => 'required',
            'harga_asli' => 'required',
        ]);
        $data['selisih'] = $data['jumlah'] * ($data['harga_asli'] - $data['harga_jual']);
        $penyusutan = penyusutan::create($data);
        $yearMonth = date('Y-m', strtotime($penyusutan->tanggal));
        $yearMonthday = date('Y-m-d', strtotime($penyusutan->tanggal));
        $kas = kas::where('nama', 'pemasukan' . $yearMonth)->get()->first();
        $kashari = kas::where('nama', 'pemasukan' . $yearMonthday)->get()->first();
        $kas1 = kas::where('nama', 'penyusutan' . $yearMonth)->get()->first();
        $kashari1 = kas::where('nama', 'penyusutan' . $yearMonthday)->get()->first();
        $balance = kas::where('nama', 'Balance')->get()->first();
        $kaskategori = kas_kategori::where('nama', 'Penyusutan ' . $penyusutan->nama_produk . $yearMonth)->get()->first();
        if ($kas == []) {
            kas::create([
                'nama' => 'pemasukan' . $yearMonth,
                'jumlah' => $penyusutan->jumlah * $penyusutan->harga_jual
            ]);
        } else {
            $kas->update([
                'jumlah' => $kas->jumlah + ($penyusutan->jumlah * $penyusutan->harga_jual)
            ]);
        }
        if ($kashari == []) {
            kas::create([
                'nama' => 'pemasukan' . $yearMonthday,
                'jumlah' => $penyusutan->jumlah * $penyusutan->harga_jual
            ]);
        } else {
            $kashari->update([
                'jumlah' => $kashari->jumlah + ($penyusutan->jumlah * $penyusutan->harga_jual)
            ]);
        }
        if ($kas1 == []) {
            kas::create([
                'nama' => 'penyusutan' . $yearMonth,
                'jumlah' => $penyusutan->selisih
            ]);
        } else {
            $kas1->update([
                'jumlah' => $kas1->jumlah + $penyusutan->selisih
            ]);
        }
        if ($kashari1 == []) {
            kas::create([
                'nama' => 'penyusutan' . $yearMonthday,
                'jumlah' => $penyusutan->selisih 
            ]);
        } else {
            $kashari1->update([
                'jumlah' => $kashari1->jumlah + $penyusutan->selisih
            ]);
        }
        if ($kaskategori == []) {
            kas_kategori::create([
                'nama' => 'Penyusutan ' . $penyusutan->nama_produk . $yearMonth,
                'jumlah' => $penyusutan->jumlah * $penyusutan->harga_jual
            ]);
        } else {
            $kaskategori->update([
                'jumlah' => $kaskategori->jumlah + ($penyusutan->jumlah * $penyusutan->harga_jual)
            ]);
        }
        $balance->update([
            'jumlah' => $balance->jumlah + ($penyusutan->jumlah * $penyusutan->harga_jual)
        ]);
        return redirect('/Penyusutan');
    }
    public function edit_form(Request $request, $id)
    {
        $penyusutan_sebelum = penyusutan::where('id', $id)->get()->first();
        $data = $request->validate([
            'tanggal' => 'required',
            'nama_produk' => 'required',
            'jumlah' => 'required',
            'harga_jual' => 'required',
            'harga_asli' => 'required',
        ]);
        $data['selisih'] = $data['jumlah'] * ($data['harga_asli'] - $data['harga_jual']);
        penyusutan::where('id', $id)->update($data);
        $yearMonth = date('Y-m', strtotime($data['tanggal']));
        $yearMonthday = date('Y-m-d', strtotime($data['tanggal']));
        $kas = kas::where('nama', 'pemasukan' . $yearMonth)->get()->first();
        $kashari = kas::where('nama', 'pemasukan' . $yearMonthday)->get()->first();
        $kas1 = kas::where('nama', 'penyusutan' . $yearMonth)->get()->first();
        $kashari1 = kas::where('nama', 'penyusutan' . $yearMonthday)->get()->first();
        $balance = kas::where('nama', 'Balance')->get()->first();
        $kaskategorisebelum = kas_kategori::where('nama', 'Penyusutan ' . $penyusutan_sebelum->nama_produk . $yearMonth)->get()->first();
        $kaskategori = kas_kategori::where('nama', 'Penyusutan ' . $data['nama_produk'] . $yearMonth)->get()->first();
        if ($kas == []) {
            kas::create([
                'nama' => 'pemasukan' . $yearMonth,
                'jumlah' => $data['jumlah'] * $data['harga_jual']
            ]);
        } else {
            $kas->update([
                'jumlah' => $kas->jumlah - ($penyusutan_sebelum->jumlah * $penyusutan_sebelum->harga_jual) + ($data['jumlah'] * $data['harga_jual'])
            ]);
        }
        if ($kashari == []) {
            kas::create([
                'nama' => 'pemasukan' . $yearMonthday,
                'jumlah' => $data['jumlah'] * $data['harga_jual']
            ]);
        } else {
            $kashari->update([
                'jumlah' => $kashari->jumlah - ($penyusutan_sebelum->jumlah * $penyusutan_sebelum->harga_jual) + ($data['jumlah'] * $data['harga_jual'])
            ]);
        }
        if ($kas1 == []) {
            kas::create([
                'nama' => 'penyusutan' . $yearMonth,
                'jumlah' => $data['selisih']
            ]);
        } else {
            $kas1->update([
                'jumlah' => $kas1->jumlah - $penyusutan_sebelum->selisih + $data['selisih']
            ]);
        }
        if ($kashari1 == []) {
            kas::create([
                'nama' => 'penyusutan' . $yearMonthday,
                'jumlah' => $data['selisih']
            ]);
        } else {
            $kashari1->update([
                'jumlah' => $kashari1->jumlah - $penyusutan_sebelum->selisih + $data['selisih']
            ]);
        }
        if ($kaskategori == []) {
            $kaskategorisebelum->update([
                'jumlah' => $kaskategorisebelum->jumlah - ($penyusutan_sebelum->jumlah * $penyusutan_sebelum->harga_jual)
            ]);
            kas_kategori::create([
                'nama' => 'Penyusutan ' . $data['nama_produk'] . $yearMonth,
                'jumlah' => ($data['jumlah'] * $data['harga_jual'])
            ]);
        } else {
            DB::table('kas_kategori')->where('id', $kaskategorisebelum->id)
                ->decrement('jumlah', ($penyusutan_sebelum->jumlah * $penyusutan_sebelum->harga_jual));

            DB::table('kas_kategori')->where('id', $kaskategori->id)
                ->increment('jumlah', ($data['jumlah'] * $data['harga_jual']));
        }
        $balance->update([
            'jumlah' => $balance->jumlah - ($penyusutan_sebelum->jumlah * $penyusutan_sebelum->harga_jual) + ($data['jumlah'] * $data['harga_jual'])
        ]);
        return redirect('/Penyusutan');
    }
    public function delete_form($id)
    {
        $penyusutan_sebelum = penyusutan::where('id', $id)->get()->first();
        $yearMonth = date('Y-m', strtotime($penyusutan_sebelum->tanggal));
        $yearMonthday = date('Y-m-d', strtotime($penyusutan_sebelum->tanggal));
        $kas = kas::where('nama', 'pemasukan' . $yearMonth)->get()->first();
        $kashari = kas::where('nama', 'pemasukan' . $yearMonthday)->get()->first();
        $kas1 = kas::where('nama', 'penyusutan' . $yearMonth)->get()->first();
        $kashari1 = kas::where('nama', 'penyusutan' . $yearMonthday)->get()->first();
        $balance = kas::where('nama', 'Balance')->get()->first();
        $kaskategori = kas_kategori::where('nama', 'Penyusutan ' . $penyusutan_sebelum->nama_produk . $yearMonth)->get()->first();
        $kas->update([
            'jumlah' => $kas->jumlah - ($penyusutan_sebelum->jumlah * $penyusutan_sebelum->harga_jual)
        ]);
        $kashari->update([
            'jumlah' => $kashari->jumlah - ($penyusutan_sebelum->jumlah * $penyusutan_sebelum->harga_jual)
        ]);
        $kas1->update([
            'jumlah' => $kas1->jumlah - $penyusutan_sebelum->selisih
        ]);
        $kashari1->update([
            'jumlah' => $kashari1->jumlah - $penyusutan_sebelum->selisih
        ]);
        $kaskategori->update([
            'jumlah' => $kaskategori->jumlah - ($penyusutan_sebelum->jumlah * $penyusutan_sebelum->harga_jual)
        ]);
        $balance->update([
            'jumlah' => $balance->jumlah - ($penyusutan_sebelum->jumlah * $penyusutan_sebelum->harga_jual)
        ]);
        $penyusutan_sebelum->delete();
        return redirect('/Penyusutan');
    }
}
