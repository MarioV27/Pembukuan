<?php

namespace App\Http\Controllers;
use App\Models\kas;
use App\Models\piutang_pemasukan;
use App\Models\piutang_pengeluaran;
use App\Models\karyawan;
use App\Models\penyusutan;
use Illuminate\Support\Carbon;

class Dashboard extends Controller
{
    public function dashboard(){
        $tglskrg = Carbon::now()->timezone('Asia/Jakarta');
        $tahun = $tglskrg->format('Y');
        $bulan = $tglskrg->format('m');
        $hari = $tglskrg->format('d');

        $pendapatan_skrg=kas::where('nama','pemasukan'.$tahun."-".$bulan)->value('jumlah');
        $pendapatan_kmrn=kas::where('nama','pemasukan'.$tahun."-".$bulan-1)->value('jumlah');
        $pengeluaran_skrg=kas::where('nama','pengeluaran'.$tahun."-".$bulan)->value('jumlah');
        $pengeluaran_kmrn=kas::where('nama','pengeluaran'.$tahun."-".$bulan-1)->value('jumlah');
        $penyusutan_skrg=penyusutan::where('tanggal','like',$tahun.'-'.$bulan.'%')->sum('selisih');
        $kas=kas::where('nama','Balance')->value('jumlah');
        $piutang_masuk=piutang_pemasukan::sum('sisa');
        $piutang_keluar=piutang_pengeluaran::sum('sisa');
        $total_gaji=karyawan::sum('gaji');

        return view('welcome',[
            'title' => 'Dashboard-SIARAN',
            'pendapatan_skrg' => $pendapatan_skrg,
            'pendapatan_kmrn' => $pendapatan_kmrn,
            'pengeluaran_skrg' => $pengeluaran_skrg,
            'pengeluaran_kmrn' => $pengeluaran_kmrn,
            'penyusutan_skrg' => $penyusutan_skrg,
            'kas' => $kas,
            'piutang_masuk' => $piutang_masuk,
            'piutang_keluar' => $piutang_keluar,
            'total_gaji' => $total_gaji,
        ]);
    }
    
}
