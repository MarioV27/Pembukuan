<?php

namespace App\Http\Controllers;
use App\Models\hutang;
use App\Models\karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cHutang extends Controller
{
    public function hutang(){
        $get = DB::table('hutang')
            ->join('karyawan', 'karyawan.id', '=', 'hutang.id_karyawan')
            ->select(
                'hutang.id',
                'hutang.tanggal',
                'karyawan.nama',
                'hutang.jumlah',
                'hutang.status',
            )->get();
        return view('Hutang.tables',[
            'data' => $get,
        ]);
    }
    public function view_form(){
        $get = karyawan::select('nama','id')->get();
        return view('Hutang.forms',[
            'title' => 'Dashboard-SIARAN',
            'data' => $get,
        ]);
    }
    public function view_edit($id){
        $get = karyawan::select('nama','id')->get();
        $gets = hutang::where('id',$id)->get()->first();
        return view('Hutang.edit',[
            'title' => 'Dashboard-SIARAN',
            'data' => $get,
            'datas' => $gets
        ]);
    }
    public function post_form(Request $request){
        $data=$request->validate([
            'tanggal'=> 'required',
            'id_karyawan'=> 'required',
            'status'=> 'required',
            'jumlah'=> 'required',
        ]);
        hutang::create($data);
        $karyawan=karyawan::where('id',$data['id_karyawan'])->get()->first();
        if($data['status']=="bayar"){
            $karyawan->update([
                'utang'=>$karyawan->utang-$data['jumlah']
            ]);
        }else if($data['status']=="ambil"){
            $karyawan->update([
                'utang'=>$karyawan->utang+$data['jumlah']
            ]);
        }
        return redirect('/Hutang');
    }
    public function edit_form(Request $request,$id){
        $hutang_sebelum = hutang::where('id',$id)->get()->first();
        $data=$request->validate([
            'tanggal'=> 'required',
            'id_karyawan'=> 'required',
            'status'=> 'required',
            'jumlah'=> 'required',
        ]);
        hutang::where('id',$id)->update($data);
        $karyawan=karyawan::where('id',$data['id_karyawan'])->get()->first();
        if($data['status']=="bayar"){
            $karyawan->update([
                'utang'=>$karyawan->utang+$hutang_sebelum->jumlah-$data['jumlah']
            ]);
        }else if($data['status']=="ambil"){
            $karyawan->update([
                'utang'=>$karyawan->utang-$hutang_sebelum->jumlah+$data['jumlah']
            ]);
        }
        return redirect('/Hutang');
    }
    public function delete_form($id){
        $hutang_sebelum = hutang::where('id',$id)->get()->first();
        $karyawan=karyawan::where('id',$hutang_sebelum->id_karyawan)->get()->first();
        if($hutang_sebelum->status=="bayar"){
            $karyawan->update([
                'utang'=>$karyawan->utang+$hutang_sebelum->jumlah
            ]);
        }else if($hutang_sebelum->status=="ambil"){
            $karyawan->update([
                'utang'=>$karyawan->utang-$hutang_sebelum->jumlah
            ]);
        }
        $hutang_sebelum->delete();
        return redirect('/Hutang');
    }
}
