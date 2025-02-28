<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\piutang_pemasukan;
use App\Models\pemasukan;
use App\Models\kas;
use Illuminate\Support\Facades\DB;

class cPiutang_Pemasukan extends Controller
{
    public function piutang_pemasukan()
    {
        $get = DB::table('piutang_pemasukan')
            ->join('pemasukan', 'pemasukan.id', '=', 'piutang_pemasukan.id_pemasukan')
            ->select(
                'piutang_pemasukan.id',
                'pemasukan.tanggal',
                'piutang_pemasukan.tanggal_tempo',
                'pemasukan.nama_produk',
                'pemasukan.total',
                'piutang_pemasukan.bayar',
                'piutang_pemasukan.sisa',
            )->get();
        return view('Piutang_Pemasukan.tables', [
            'data' => $get,
        ]);
    }
    public function view_form($id)
    {
        return view('Piutang_Pemasukan.forms', [
            'title' => 'Dashboard-SIARAN',
            'id' => $id
        ]);
    }
    public function post_form(Request $request, $id)
    {
        $bayar = $request->bayar;
        $piutang = piutang_pemasukan::where('id', $id)->get()->first();
        if ($bayar < $piutang->sisa) {
            $piutang->update([
                'bayar' => $piutang->bayar + $bayar,
                'sisa' => $piutang->sisa - $bayar
            ]);
            return redirect('/Piutang_Pemasukan');
        } else if ($bayar == $piutang->sisa) {
            $pemasukan = pemasukan::where('id', $piutang->id_pemasukan)->get()->first();
            $piutang->delete();
            $pemasukan->update(['status' => 'lunas']);
            $yearMonth = date('Y-m', strtotime($pemasukan->tanggal));
            $yearMonthday = date('Y-m-d', strtotime($pemasukan->tanggal));
            $kas = kas::where('nama', 'pemasukan' . $yearMonth)->get()->first();
            $kasharian = kas::where('nama', 'pemasukan' . $yearMonthday)->get()->first();
            $balance = kas::where('nama', 'Balance')->get()->first();
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
            if ($kasharian == []) {
                kas::create([
                    'nama' => 'pemasukan' . $yearMonthday,
                    'jumlah' => $pemasukan->total
                ]);
            } else {
                $kasharian->update([
                    'jumlah' => $kasharian->jumlah + $pemasukan->total
                ]);
            }
            $balance->update([
                'jumlah' => $balance->jumlah + $pemasukan->total
            ]);
            return redirect('/Piutang_Pemasukan');
        } else {
            return redirect('/Piutang_Pemasukan/form/' . $id);
        }
    }
    public function delete_form($id)
    {
        $piutang = piutang_pemasukan::where('id', $id)->get()->first();
        $pemasukan = pemasukan::where('id', $piutang->id_pemasukan)->get()->first();
        $piutang->delete();
        $pemasukan->update(['status' => 'lunas']);
        $yearMonth = date('Y-m', strtotime($pemasukan->tanggal));
        $yearMonthday = date('Y-m-d', strtotime($pemasukan->tanggal));
        $kas = kas::where('nama', 'pemasukan' . $yearMonth)->get()->first();
        $kasharian = kas::where('nama', 'pemasukan' . $yearMonthday)->get()->first();
        $balance = kas::where('nama', 'Balance')->get()->first();
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
        if ($kasharian == []) {
            kas::create([
                'nama' => 'pemasukan' . $yearMonthday,
                'jumlah' => $pemasukan->total
            ]);
        } else {
            $kasharian->update([
                'jumlah' => $kasharian->jumlah + $pemasukan->total
            ]);
        }
        $balance->update([
            'jumlah' => $balance->jumlah + $pemasukan->total
        ]);
        return redirect('/Piutang_Pemasukan');
    }

    public function delete_formarr(Request $request)
{
    $selectedItems = explode(',', $request->selectedItems); // Mengambil ID yang dikirim via URL

    foreach ($selectedItems as $id) {
        // Temukan data berdasarkan ID piutang
        $piutang = piutang_pemasukan::find($id);

        if ($piutang) {
            // Temukan data pemasukan terkait
            $pemasukan = pemasukan::find($piutang->id_pemasukan);
            
            // Hapus data piutang dan update pemasukan
            $piutang->delete();
            if ($pemasukan) {
                $pemasukan->update(['status' => 'lunas']);
                $yearMonth = date('Y-m', strtotime($pemasukan->tanggal));
                $yearMonthday = date('Y-m-d', strtotime($pemasukan->tanggal));
                $kas = kas::where('nama', 'pemasukan' . $yearMonth)->first();
                $kasharian = kas::where('nama', 'pemasukan' . $yearMonthday)->first();
                $balance = kas::where('nama', 'Balance')->first();
                if (!$kas) {
                    kas::create([
                        'nama' => 'pemasukan' . $yearMonth,
                        'jumlah' => $pemasukan->total
                    ]);
                } else {
                    $kas->update([
                        'jumlah' => $kas->jumlah + $pemasukan->total
                    ]);
                }
                if (!$kasharian) {
                    kas::create([
                        'nama' => 'pemasukan' . $yearMonthday,
                        'jumlah' => $pemasukan->total
                    ]);
                } else {
                    $kasharian->update([
                        'jumlah' => $kasharian->jumlah + $pemasukan->total
                    ]);
                }
                $balance->update([
                    'jumlah' => $balance->jumlah + $pemasukan->total
                ]);
            }
        }
    }

    return redirect('/Piutang_Pemasukan');
}

}
