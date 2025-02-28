<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\piutang_pengeluaran;
use App\Models\pengeluaran;
use App\Models\kas;
use Illuminate\Support\Facades\DB;

class cPiutang_Pengeluaran extends Controller
{
    public function piutang_pengeluaran()
    {
        $get = DB::table('piutang_pengeluaran')
            ->join('pengeluaran', 'pengeluaran.id', '=', 'piutang_pengeluaran.id_pengeluaran')
            ->select(
                'piutang_pengeluaran.id',
                'pengeluaran.tanggal',
                'piutang_pengeluaran.tanggal_tempo',
                'pengeluaran.total',
                'pengeluaran.kategori',
                'piutang_pengeluaran.bayar',
                'piutang_pengeluaran.sisa',
            )->get();
        return view('Piutang_Pengeluaran.tables', [
            'data' => $get,
        ]);
    }
    public function view_form($id)
    {
        return view('Piutang_Pengeluaran.forms', [
            'title' => 'Dashboard-SIARAN',
            'id' => $id
        ]);
    }
    public function post_form(Request $request, $id)
    {
        $bayar = $request->bayar;
        $piutang = piutang_pengeluaran::where('id', $id)->get()->first();
        if ($bayar < $piutang->sisa) {
            $piutang->update([
                'bayar' => $piutang->bayar + $bayar,
                'sisa' => $piutang->sisa - $bayar
            ]);
            return redirect('/Piutang_Pengeluaran');
        } else if ($bayar == $piutang->sisa) {
            $pengeluaran = pengeluaran::where('id', $piutang->id_pengeluaran)->get()->first();
            $piutang->delete();
            $pengeluaran->update(['status' => 'lunas']);
            $yearMonth = date('Y-m', strtotime($pengeluaran->tanggal));
            $yearMonthday = date('Y-m-d', strtotime($pengeluaran->tanggal));
            $kas = kas::where('nama', 'pengeluaran' . $yearMonth)->get()->first();
            $kasharian = kas::where('nama', 'pengeluaran' . $yearMonthday)->get()->first();
            $balance = kas::where('nama', 'Balance')->get()->first();
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
            if ($kasharian == []) {
                kas::create([
                    'nama' => 'pemasukan' . $yearMonthday,
                    'jumlah' => $pengeluaran->total
                ]);
            } else {
                $kasharian->update([
                    'jumlah' => $kasharian->jumlah + $pengeluaran->total
                ]);
            }
            $balance->update([
                'jumlah' => $balance->jumlah + $pengeluaran->total
            ]);
            return redirect('/Piutang_Pengeluaran');
        } else {
            return redirect('/Piutang_Pengeluaran/form/' . $id);
        }
    }
    public function delete_form($id)
    {
        $piutang = piutang_pengeluaran::where('id', $id)->get()->first();
        $pengeluaran = pengeluaran::where('id', $piutang->id_pengeluaran)->get()->first();
        $piutang->delete();
        $pengeluaran->update(['status' => 'lunas']);
        $yearMonth = date('Y-m', strtotime($pengeluaran->tanggal));
        $yearMonthday = date('Y-m-d', strtotime($pengeluaran->tanggal));
        $kas = kas::where('nama', 'pengeluaran' . $yearMonth)->get()->first();
        $kasharian = kas::where('nama', 'pengeluaran' . $yearMonthday)->get()->first();
        $balance = kas::where('nama', 'Balance')->get()->first();
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
        if ($kasharian == []) {
            kas::create([
                'nama' => 'pengeluaran' . $yearMonthday,
                'jumlah' => $pengeluaran->total
            ]);
        } else {
            $kasharian->update([
                'jumlah' => $kasharian->jumlah + $pengeluaran->total
            ]);
        }
        $balance->update([
            'jumlah' => $balance->jumlah + $pengeluaran->total
        ]);
        return redirect('/Piutang_Pengeluaran');
    }

    public function delete_formarr(Request $request)
    {
        $selectedItems = explode(',', $request->selectedItems); // Mengambil ID yang dikirim via URL

        foreach ($selectedItems as $id) {
            $piutang = piutang_pengeluaran::where('id', $id)->get()->first();
            $pengeluaran = pengeluaran::where('id', $piutang->id_pengeluaran)->get()->first();
            $piutang->delete();
            $pengeluaran->update(['status' => 'lunas']);
            $yearMonth = date('Y-m', strtotime($pengeluaran->tanggal));
            $yearMonthday = date('Y-m-d', strtotime($pengeluaran->tanggal));
            $kas = kas::where('nama', 'pengeluaran' . $yearMonth)->get()->first();
            $kasharian = kas::where('nama', 'pengeluaran' . $yearMonthday)->get()->first();
            $balance = kas::where('nama', 'Balance')->get()->first();
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
            if ($kasharian == []) {
                kas::create([
                    'nama' => 'pengeluaran' . $yearMonthday,
                    'jumlah' => $pengeluaran->total
                ]);
            } else {
                $kasharian->update([
                    'jumlah' => $kasharian->jumlah + $pengeluaran->total
                ]);
            }
            $balance->update([
                'jumlah' => $balance->jumlah + $pengeluaran->total
            ]);
        }

        return redirect('/Piutang_Pemasukan');
    }
}
