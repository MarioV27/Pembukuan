<?php

namespace App\Http\Controllers;

use App\Models\gaji;
use App\Models\karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cGaji extends Controller
{
    public function gaji()
    {
        $get = DB::table('gaji')
            ->join('karyawan', 'karyawan.id', '=', 'gaji.id_karyawan')
            ->select(
                'gaji.id',
                'gaji.tanggal',
                'karyawan.nama',
                'gaji.jumlah',
            )->get();
        return view('Gaji.tables', [
            'data' => $get,
        ]);
    }
    public function view_form()
    {
        $get = karyawan::select('nama', 'id')->get();
        return view('Gaji.forms', [
            'title' => 'Dashboard-SIARAN',
            'data' => $get,
        ]);
    }
    public function view_edit($id)
    {
        $get = karyawan::select('nama', 'id')->get();
        $gets = gaji::where('id', $id)->get()->first();
        return view('Gaji.edit', [
            'title' => 'Dashboard-SIARAN',
            'data' => $get,
            'datas' => $gets
        ]);
    }
    public function post_form(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required',
            'id_karyawan' => 'required',
            'jumlah' => 'required',
        ]);
        gaji::create($data);
        $karyawan = karyawan::where('id', $data['id_karyawan'])->get()->first();
        $karyawan->update([
            'gaji' => $karyawan->gaji + $data['jumlah']
        ]);
        return redirect('/Gaji');
    }
    public function edit_form(Request $request,$id){
        $gaji_sebelum = gaji::where('id',$id)->get()->first();
        $data=$request->validate([
            'tanggal'=> 'required',
            'id_karyawan'=> 'required',
            'jumlah'=> 'required',
        ]);
        gaji::where('id',$id)->update($data);
        $karyawan=karyawan::where('id',$data['id_karyawan'])->get()->first();
            $karyawan->update([
                'gaji'=>$karyawan->gaji-$gaji_sebelum->jumlah+$data['jumlah']
            ]);
        return redirect('/Gaji');
    }
    public function delete_form($id){
        $gaji_sebelum = gaji::where('id',$id)->get()->first();
        $karyawan=karyawan::where('id',$gaji_sebelum->id_karyawan)->get()->first();
            $karyawan->update([
                'utang'=>$karyawan->utang-$gaji_sebelum->jumlah
            ]);
        $gaji_sebelum->delete();
        return redirect('/Gaji');
    }
}
