<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pengeluaran;
use App\Models\piutang_pengeluaran;
use App\Models\kas;
use App\Models\kas_kategori;
use App\Models\kategori;
use Illuminate\Support\Facades\DB;

class cPengeluaran extends Controller
{
    public function pengeluaran()
    {
        $get = pengeluaran::where('status', 'lunas')->get();
        return view('Pengeluaran.tables', [
            'data' => $get,
        ]);
    }
    public function view_form()
    {
        $kategori = kategori::where('kategori', 'Kategori_Pengeluaran')->orwhere('kategori', 'Kategori_Pengeluaran_Biaya')->get();
        $supplier = kategori::where('kategori', 'Supplier_Pengeluaran')->get();
        return view('Pengeluaran.forms', [
            'title' => 'Dashboard-SIARAN',
            'kategori' => $kategori,
            'supplier' => $supplier
        ]);
    }
    public function view_edit($id)
    {
        $kategori = kategori::where('kategori', 'Kategori_Pengeluaran')->orwhere('kategori', 'Kategori_Pengeluaran_Biaya')->get();
        $supplier = kategori::where('kategori', 'Supplier_Pengeluaran')->get();
        $get = pengeluaran::where('id', $id)->get()->first();
        return view('Pengeluaran.edit', [
            'data' => $get,
            'kategori' => $kategori,
            'supplier' => $supplier
        ]);
    }
    public function post_form(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required',
            'kategori' => 'required',
            'supplier' => 'required',
            'jumlah' => 'required',
            'harga' => 'required',
            'keterangan' => 'required',
            'status' => 'required',
        ]);
        $data['total'] = $data['jumlah'] * $data['harga'];
        $pengeluaran = pengeluaran::create($data);
        if ($data['status'] == "piutang") {
            $datas = [
                'tanggal_tempo' =>  $request->tanggal_tempo,
                'bayar' => 0,
                'sisa' => $pengeluaran->total,
                'id_pengeluaran' => $pengeluaran->id,
            ];
            piutang_pengeluaran::create($datas);
            return redirect('/Piutang_Pengeluaran');
        } else {
            $yearMonth = date('Y-m', strtotime($pengeluaran->tanggal));
            $yearMonthday = date('Y-m-d', strtotime($pengeluaran->tanggal));
            $kas = kas::where('nama', 'pengeluaran' . $yearMonth)->get()->first();
            $kashari = kas::where('nama', 'pengeluaran' . $yearMonthday)->get()->first();
            $balance = kas::where('nama', 'Balance')->get()->first();
            $kategori = kas_kategori::where('nama', 'Pengeluaran ' . $pengeluaran->kategori . $yearMonth)->get()->first();
            $supplier = kas_kategori::where('nama', 'Pengeluaran ' . $pengeluaran->supplier . $yearMonth)->get()->first();
            if ($kas == []) {
                kas::create([
                    'nama' => 'pengeluaran' . $yearMonth,
                    'jumlah' => $pengeluaran->total
                ]);
            } else {
                $kas->update([
                    'jumlah' => $kas->jumlah + $pengeluaran->total
                ]);
            }
            if ($kashari == []) {
                kas::create([
                    'nama' => 'pengeluaran' . $yearMonthday,
                    'jumlah' => $pengeluaran->total
                ]);
            } else {
                $kashari->update([
                    'jumlah' => $kashari->jumlah + $pengeluaran->total
                ]);
            }
            if ($kategori == []) {
                kas_kategori::create([
                    'nama' => 'Pengeluaran ' . $pengeluaran->kategori . $yearMonth,
                    'jumlah' => $pengeluaran->total
                ]);
            } else {
                $kategori->update([
                    'jumlah' => $kategori->jumlah + $pengeluaran->total
                ]);
            }
            if ($supplier == []) {
                kas_kategori::create([
                    'nama' => 'Pengeluaran ' . $pengeluaran->supplier . $yearMonth,
                    'jumlah' => $pengeluaran->total
                ]);
            } else {
                $supplier->update([
                    'jumlah' => $supplier->jumlah + $pengeluaran->total
                ]);
            }
            $balance->update([
                'jumlah' => $balance->jumlah - $pengeluaran->total
            ]);
            return redirect('/Pengeluaran');
        }
        return ($data);
    }
    public function edit_form(Request $request, $id)
    {
        $pengeluaran_sebelum = pengeluaran::where('id', $id)->get()->first();
        $data = $request->validate([
            'tanggal' => 'required',
            'kategori' => 'required',
            'supplier' => 'required',
            'jumlah' => 'required',
            'harga' => 'required',
            'keterangan' => 'required',
            'status' => 'required',
        ]);
        $data['total'] = $data['jumlah'] * $data['harga'];
        pengeluaran::where('id', $id)->update($data);
        $yearMonth = date('Y-m', strtotime($data['tanggal']));
        $yearMonthday = date('Y-m-d', strtotime($data['tanggal']));
        $kas = kas::where('nama', 'pengeluaran' . $yearMonth)->get()->first();
        $kashari = kas::where('nama', 'pengeluaran' . $yearMonthday)->get()->first();
        $balance = kas::where('nama', 'Balance')->get()->first();
        $kategori = kas_kategori::where('nama', 'Pengeluaran ' . $data['kategori'] . $yearMonth)->get()->first();
        $supplier = kas_kategori::where('nama', 'Pengeluaran ' . $data['supplier'] . $yearMonth)->get()->first();
        $kategorisebelum = kas_kategori::where('nama', 'Pengeluaran ' . $pengeluaran_sebelum->kategori . $yearMonth)->get()->first();
        $suppliersebelum = kas_kategori::where('nama', 'Pengeluaran ' . $pengeluaran_sebelum->supplier . $yearMonth)->get()->first();
        if ($data['status'] == "piutang") {
            $datas = [
                'tanggal_tempo' =>  $request->tanggal_tempo,
                'bayar' => 0,
                'sisa' => $data['total'],
                'id_pengeluaran' => $id,
            ];
            piutang_pengeluaran::create($datas);
            $kas->update([
                'jumlah' => $kas->jumlah - $pengeluaran_sebelum->total
            ]);
            return redirect('/Piutang_Pengeluaran');
        } else {
            if ($kas == []) {
                kas::create([
                    'nama' => 'pengeluaran' . $yearMonth,
                    'jumlah' => $data['total']
                ]);
            } else {
                $kas->update([
                    'jumlah' => $kas->jumlah - $pengeluaran_sebelum->total + $data['total']
                ]);
            }
            if ($kashari == []) {
                kas::create([
                    'nama' => 'pengeluaran' . $yearMonthday,
                    'jumlah' => $data['total']
                ]);
            } else {
                $kashari->update([
                    'jumlah' => $kashari->jumlah - $pengeluaran_sebelum->total + $data['total']
                ]);
            }
            if ($kategori == []) {
                $kategorisebelum->update([
                    'jumlah' => $kategorisebelum->jumlah - $pengeluaran_sebelum->total
                ]);
                kas_kategori::create([
                    'nama' => 'Pengeluaran ' . $data['kategori'] . $yearMonth,
                    'jumlah' => $data['total']
                ]);
            } else {
                DB::table('kas_kategori')->where('id', $kategorisebelum->id)
                    ->decrement('jumlah', $pengeluaran_sebelum->total);

                DB::table('kas_kategori')->where('id', $kategori->id)
                    ->increment('jumlah', $data['total']);
            }
            if ($supplier == []) {
                $suppliersebelum->update([
                    'jumlah' => $suppliersebelum->jumlah - $pengeluaran_sebelum->total
                ]);
                kas_kategori::create([
                    'nama' => 'Pengeluaran ' . $data['supplier'] . $yearMonth,
                    'jumlah' => $data['total']
                ]);
            } else {
                DB::table('kas_kategori')->where('id', $suppliersebelum->id)
                    ->decrement('jumlah', $pengeluaran_sebelum->total);

                DB::table('kas_kategori')->where('id', $supplier->id)
                    ->increment('jumlah', $data['total']);
            }
            $balance->update([
                'jumlah' => $balance->jumlah + $pengeluaran_sebelum->total - $data['total']
            ]);
            return redirect('/Pengeluaran');
        }
        return ($data);
    }
    public function delete_form($id)
    {
        $pengeluaran_sebelum = pengeluaran::where('id', $id)->get()->first();
        $yearMonth = date('Y-m', strtotime($pengeluaran_sebelum->tanggal));
        $yearMonthday = date('Y-m-d', strtotime($pengeluaran_sebelum->tanggal));
        $kashari = kas::where('nama', 'pengeluaran' . $yearMonthday)->get()->first();
        $balance = kas::where('nama', 'Balance')->get()->first();
        $kas = kas::where('nama', 'pengeluaran' . $yearMonth)->get()->first();
        $kategori = kas_kategori::where('nama', 'Pengeluaran ' . $pengeluaran_sebelum->kategori . $yearMonth)->get()->first();
        $supplier = kas_kategori::where('nama', 'Pengeluaran ' . $pengeluaran_sebelum->supplier . $yearMonth)->get()->first();
        $kas->update([
            'jumlah' => $kas->jumlah - $pengeluaran_sebelum->total
        ]);
        $kashari->update([
            'jumlah' => $kashari->jumlah - $pengeluaran_sebelum->total
        ]);
        $kategori->update([
            'jumlah' => $kategori->jumlah - $pengeluaran_sebelum->total
        ]);
        $supplier->update([
            'jumlah' => $supplier->jumlah - $pengeluaran_sebelum->total
        ]);
        $balance->update([
            'jumlah' => $balance->jumlah + $pengeluaran_sebelum->total
        ]);
        $pengeluaran_sebelum->delete();
        return redirect('/Pengeluaran');
    }
}
