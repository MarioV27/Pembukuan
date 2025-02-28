<?php

namespace App\Http\Controllers;
use App\Models\karyawan;
use Illuminate\Http\Request;
use App\Models\gaji;
use App\Models\hutang;

class cKaryawan extends Controller
{
    public function karyawan(){
        $get = karyawan::get();
        return view('Karyawan.tables',[
            'data' => $get,
        ]);
    }
    public function view_form(){

        return view('Karyawan.forms',[
            'title' => 'Dashboard-SIARAN',
        ]);
    }
    public function view_edit($id)
    {
        $get = karyawan::where('id',$id)->get()->first();
        return view('Karyawan.edit', [
            'data' => $get
        ]);
    }
    public function post_form(Request $request){
        $data=$request->validate([
            'nama'=> 'required',
            'gaji'=> 'required',
            'utang'=> 'required',
            'thr'=> 'required',
            'kebersihan'=> 'required',
            'bonus'=> 'required'
        ]);
        // $data['total'] = ($data['jumlah']*$data['harga'])+$data['penambahan'];
        karyawan::create($data);
        return redirect('/Karyawan');
    }
    public function edit_form(Request $request,$id){
        $data=$request->validate([
            'nama'=> 'required',
            'gaji'=> 'required',
            'utang'=> 'required',
            'thr'=> 'required',
            'kebersihan'=> 'required',
            'bonus'=> 'required'
        ]);
        // $data['total'] = ($data['jumlah']*$data['harga'])+$data['penambahan'];
        karyawan::where('id',$id)->update($data);
        return redirect('/Karyawan');
    }
    public function delete_form($id){
        karyawan::where('id',$id)->delete();
        gaji::where('id_karyawan',$id)->delete();
        hutang::where('id_karyawan',$id)->delete();
        return redirect('/Karyawan');
    }
}
