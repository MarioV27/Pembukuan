<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class cKategori extends Controller
{
    public function kategori()
    {

        $get = kategori::get();
        return view('Kategori.tables', [
            'data' => $get,
        ]);
    }
    public function view_form()
    {

        return view('Kategori.forms', [
            'title' => 'Dashboard-SIARAN',
        ]);
    }
    public function view_edit($id)
    {
        $get = kategori::where('id',$id)->get()->first();
        return view('Kategori.edit', [
            'datas' => $get
        ]);
    }
    public function post_form(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
        ]);
        kategori::create($data);
            return redirect('/Kategori');
        
    }
    public function edit_form(Request $request,$id){
        $data = $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
        ]);
        kategori::where('id',$id)->update($data);
            return redirect('/Kategori');
    }
    public function delete_form($id){
        kategori::where('id',$id)->delete();
            return redirect('/Kategori');
    }
}
