<?php

namespace App\Http\Controllers;

use App\Models\aset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cAset extends Controller
{
    public function aset()
    {
        $get = aset::get();
        return view('Aset.tables', [
            'data' => $get,
        ]);
    }
    public function view_form()
    {
        return view('Aset.forms', [
            'title' => 'Dashboard-SIARAN',
        ]);
    }
    public function view_edit($id)
    {
        $gets = aset::where('id', $id)->get()->first();
        return view('Aset.edit', [
            'title' => 'Dashboard-SIARAN',
            'datas' => $gets
        ]);
    }
    public function post_form(Request $request)
    {
        $data = $request->validate([
            'id_karyawan' => 'required',
            'jumlah' => 'required',
        ]);
        aset::create($data);
        return redirect('/Aset');
    }
    public function edit_form(Request $request,$id){
        $data=$request->validate([
            'id_karyawan'=> 'required',
            'jumlah'=> 'required',
        ]);
        aset::where('id',$id)->update($data);
        return redirect('/Aset');
    }
    public function delete_form($id){
        $gaji_sebelum = aset::where('id',$id)->get()->first();
        $gaji_sebelum->delete();
        return redirect('/Aset');
    }
}
