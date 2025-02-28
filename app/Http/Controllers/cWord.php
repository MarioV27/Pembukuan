<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kas;
use App\Models\kas_kategori;
use App\Models\kategori;

class cWord extends Controller
{
    public function word(Request $request)
    {
        $phpword = new \PhpOffice\PhpWord\TemplateProcessor('C:\Users\ACER\Documents\test.docx');
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $pemasukan = kas::where('nama', 'pemasukan' . $tahun . '-' . $bulan)->get()->first();
        $jenispengeluaran = kategori::where('kategori', 'Kategori_Pengeluaran')->pluck('nama');
        $jenispengeluaranarray = $jenispengeluaran->map(function ($item) use ($tahun, $bulan) {
            return 'Pengeluaran ' . $item  . $tahun . '-' . $bulan;
        })
            ->toArray();
        $jenispengeluaranString = implode(', ', $jenispengeluaran->toArray());
        $pengeluaran = kas_kategori::whereIn('nama', $jenispengeluaranarray)->sum('jumlah');
        $jenisbiaya = kategori::where('kategori', 'Kategori_Pengeluaran_Biaya')->pluck('nama');
        $jenisbiayaarray = $jenisbiaya->map(function ($item) use ($tahun, $bulan) {
            return 'Pengeluaran ' . $item  . $tahun . '-' . $bulan;
        })
            ->toArray();
        $kas_kategori = kas_kategori::whereIn('nama', $jenisbiayaarray)->get();

        $dataArray = [];
        $biaya = 0;
        foreach ($kas_kategori as $item) {
            $biaya = $biaya + $item->jumlah;
            $dataArray[] = [
                'nama'   => str_replace("Pengeluaran ", "", substr($item->nama, 0, -7)),
                'pengeluaran_tunggal' => $item->jumlah
            ];
        }
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        $namaBulan = $namaBulan[$bulan];
        $laba_kotor = $pemasukan->jumlah - $pengeluaran;
        $phpword->setValues([
            'bulan' => $namaBulan,
            'tahun' => $tahun,
            'pemasukan' => $pemasukan->jumlah,
            'jenispengeluaran' => $jenispengeluaranString,
            'pengeluaran' => $pengeluaran,
            'laba_kotor' => $laba_kotor,
            'total_biaya' => $biaya,
            'laba_bersih' => $laba_kotor - $biaya
        ]);

        $phpword->cloneRowAndSetValues('nama', $dataArray);

        // $phpword->saveAs('C:\Users\ACER\Documents\test_output.docx');

        $phpword->saveAs('BAP_Perwalian.docx');
        return response()->download('BAP_Perwalian.docx')->deleteFileAfterSend(true);
    }

    public function wordTahun(Request $request)
    {
        $phpword = new \PhpOffice\PhpWord\TemplateProcessor('C:\Users\ACER\Documents\test.docx');
        $bulan = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $tahun = $request->tahun;
        $pemasukan = kas::where(function ($query) use ($tahun, $bulan) {
            foreach ($bulan as $m) {
                $query->orWhere('nama','pemasukan' . $tahun . '-' . $m);
            }
        })->sum('jumlah');
        $jenispengeluaran = kategori::where('kategori', 'Kategori_Pengeluaran')->pluck('nama');
        $jenispengeluaranarray = $jenispengeluaran->flatMap(function ($item) use ($tahun, $bulan) {
            return collect($bulan)->map(function ($m) use ($item, $tahun) {
                return 'Pengeluaran ' . $item . $tahun . '-' . $m;
            });
        })->toArray();
        $jenispengeluaranString = implode(', ', $jenispengeluaran->toArray());
        $pengeluaran = kas_kategori::whereIn('nama', $jenispengeluaranarray)->sum('jumlah');
        $jenisbiaya = kategori::where('kategori', 'Kategori_Pengeluaran_Biaya')->pluck('nama');
        $jenisbiayaarray = $jenisbiaya->flatMap(function ($item) use ($tahun, $bulan) {
            return collect($bulan)->map(function ($m) use ($item, $tahun) {
                return 'Pengeluaran ' . $item . $tahun . '-' . $m;
            });})
            ->toArray();
        $kas_kategori = kas_kategori::whereIn('nama', $jenisbiayaarray)->get();

        $dataArray = [];
        $biaya = 0;
        foreach ($kas_kategori as $item) {
            $biaya = $biaya + $item->jumlah;
            $dataArray[] = [
                'nama'   => str_replace("Pengeluaran ", "", substr($item->nama, 0, -7)),
                'pengeluaran_tunggal' => $item->jumlah
            ];
        }
        $namaBulan = '';
        $laba_kotor = $pemasukan - $pengeluaran;
        $phpword->setValues([
            'bulan' => $namaBulan,
            'tahun' => $tahun,
            'pemasukan' => $pemasukan,
            'jenispengeluaran' => $jenispengeluaranString,
            'pengeluaran' => $pengeluaran,
            'laba_kotor' => $laba_kotor,
            'total_biaya' => $biaya,
            'laba_bersih' => $laba_kotor - $biaya
        ]);

        $phpword->cloneRowAndSetValues('nama', $dataArray);

        // $phpword->saveAs('C:\Users\ACER\Documents\test_output.docx');

        $phpword->saveAs('BAP_Perwalian.docx');
        // return response()->json($pengeluaran);
        return response()->download('BAP_Perwalian.docx')->deleteFileAfterSend(true);

    }
}
