<?php

namespace App\Http\Controllers;

use App\Models\pemasukan;
use App\Models\piutang_pemasukan;
use App\Models\kas;
use App\Models\kas_kategori;
use App\Models\kategori;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cPemasukan extends Controller
{
    public function pemasukan()
    {

        $get = pemasukan::where('status', 'lunas')->get();
        return view('Pemasukan.tables', [
            'data' => $get,
        ]);
    }
    public function view_form()
    {
        $get = kategori::where('kategori', 'Pemasukan')->get();
        return view('Pemasukan.forms', [
            'title' => 'Dashboard-SIARAN',
            'data' => $get
        ]);
    }
    public function view_edit($id)
    {
        $gets = kategori::where('kategori', 'Pemasukan')->get();
        $get = pemasukan::where('id', $id)->get()->first();
        return view('Pemasukan.edit', [
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
            'diskon' => 'required',
            'harga' => 'required',
            'penambahan' => 'required',
            'status' => 'required',
        ]);
        $data['total'] = ($data['jumlah'] * ($data['harga'])) + $data['penambahan'] - $data['diskon'];
        $pemasukan = pemasukan::create($data);
        if ($data['status'] == "piutang") {
            $datas = [
                'tanggal_tempo' => $request->tanggal_tempo,
                'bayar' => 0,
                'sisa' => $pemasukan->total,
                'id_pemasukan' => $pemasukan->id,
            ];
            piutang_pemasukan::create($datas);
            return redirect('/Piutang_Pemasukan');
        } else {
            $yearMonth = date('Y-m', strtotime($pemasukan->tanggal));
            $yearMonthday = date('Y-m-d', strtotime($pemasukan->tanggal));
            $kas = kas::where('nama', 'pemasukan' . $yearMonth)->get()->first();
            $kashari = kas::where('nama', 'pemasukan' . $yearMonthday)->get()->first();
            $balance = kas::where('nama', 'Balance')->get()->first();
            $kaskategori = kas_kategori::where('nama', 'Pemasukan ' . $pemasukan->nama_produk . $yearMonth)->get()->first();
            if ($kas == []) {
                kas::create([
                    'nama' => 'pemasukan' . $yearMonth,
                    'jumlah' => $pemasukan->total
                ]);
            } else {
                $kas->update([
                    'jumlah' => $kas->jumlah + $pemasukan->total
                ]);
            }
            if ($kashari == []) {
                kas::create([
                    'nama' => 'pemasukan' . $yearMonthday,
                    'jumlah' => $pemasukan->total
                ]);
            } else {
                $kashari->update([
                    'jumlah' => $kashari->jumlah + $pemasukan->total
                ]);
            }
            if ($kaskategori == []) {
                kas_kategori::create([
                    'nama' => 'Pemasukan ' . $pemasukan->nama_produk . $yearMonth,
                    'jumlah' => $pemasukan->total
                ]);
            } else {
                $kaskategori->update([
                    'jumlah' => $kaskategori->jumlah + $pemasukan->total
                ]);
            }
            $balance->update([
                'jumlah' => $balance->jumlah + $pemasukan->total
            ]);
            return redirect('/Pemasukan');
        }
    }
    public function edit_form(Request $request, $id)
    {
        $pemasukan_sebelum = pemasukan::where('id', $id)->get()->first();
        $data = $request->validate([
            'tanggal' => 'required',
            'nama_produk' => 'required',
            'jumlah' => 'required',
            'diskon' => 'required',
            'harga' => 'required',
            'penambahan' => 'required',
            'status' => 'required',
        ]);
        $data['total'] = ($data['jumlah'] * ($data['harga'])) + $data['penambahan'] - $data['diskon'];
        pemasukan::where('id', $id)->update($data);
        $yearMonth = date('Y-m', strtotime($data['tanggal']));
        $yearMonthday = date('Y-m-d', strtotime($data['tanggal']));
        $kas = kas::where('nama', 'pemasukan' . $yearMonth)->get()->first();
        $kashari = kas::where('nama', 'pemasukan' . $yearMonthday)->get()->first();
        $balance = kas::where('nama', 'Balance')->get()->first();
        $kaskategorisebelum = kas_kategori::where('nama', 'Pemasukan ' . $pemasukan_sebelum->nama_produk . $yearMonth)->get()->first();
        $kaskategori = kas_kategori::where('nama', 'Pemasukan ' . $data['nama_produk'] . $yearMonth)->get()->first();
        if ($data['status'] == "piutang") {
            $datas = [
                'tanggal_tempo' => $request->tanggal_tempo,
                'bayar' => 0,
                'sisa' => $data['total'],
                'id_pemasukan' => $id,
            ];
            piutang_pemasukan::create($datas);
            $kas->update([
                'jumlah' => $kas->jumlah - $pemasukan_sebelum->total
            ]);
            return redirect('/Piutang_Pemasukan');
        } else {
            if ($kas == []) {
                kas::create([
                    'nama' => 'pemasukan' . $yearMonth,
                    'jumlah' => $data['total']
                ]);
            } else {
                $kas->update([
                    'jumlah' => $kas->jumlah - $pemasukan_sebelum->total + $data['total']
                ]);
            }
            if ($kashari == []) {
                kas::create([
                    'nama' => 'pemasukan' . $yearMonthday,
                    'jumlah' => $data['total']
                ]);
            } else {
                $kashari->update([
                    'jumlah' => $kashari->jumlah - $pemasukan_sebelum->total + $data['total']
                ]);
            }
            if ($kaskategori == []) {
                $kaskategorisebelum->update([
                    'jumlah' => $kaskategorisebelum->jumlah - $pemasukan_sebelum->total
                ]);
                kas_kategori::create([
                    'nama' => 'Pemasukan ' . $data['nama_produk'] . $yearMonth,
                    'jumlah' => $data['total']
                ]);
            } else {
                DB::table('kas_kategori')->where('id', $kaskategorisebelum->id)
                    ->decrement('jumlah', $pemasukan_sebelum->total);

                DB::table('kas_kategori')->where('id', $kaskategori->id)
                    ->increment('jumlah', $data['total']);
            }
            $balance->update([
                'jumlah' => $balance->jumlah - $pemasukan_sebelum->total + $data['total']
            ]);
            return redirect('/Pemasukan');
        }
    }
    public function delete_form($id)
    {
        $pemasukan_sebelum = pemasukan::where('id', $id)->get()->first();
        $yearMonth = date('Y-m', strtotime($pemasukan_sebelum->tanggal));
        $yearMonthday = date('Y-m-d', strtotime($pemasukan_sebelum->tanggal));
        $kas = kas::where('nama', 'pemasukan' . $yearMonth)->get()->first();
        $kashari = kas::where('nama', 'pemasukan' . $yearMonthday)->get()->first();
        $balance = kas::where('nama', 'Balance')->get()->first();
        $kaskategori = kas_kategori::where('nama', 'Pemasukan ' . $pemasukan_sebelum->nama_produk . $yearMonth)->get()->first();
        $kas->update([
            'jumlah' => $kas->jumlah - $pemasukan_sebelum->total
        ]);
        $kashari->update([
            'jumlah' => $kashari->jumlah - $pemasukan_sebelum->total
        ]);
        $kaskategori->update([
            'jumlah' => $kaskategori->jumlah - $pemasukan_sebelum->total
        ]);
        $balance->update([
            'jumlah' => $balance->jumlah - $pemasukan_sebelum->total
        ]);
        $pemasukan_sebelum->delete();
        return redirect('/Pemasukan');
    }
}
