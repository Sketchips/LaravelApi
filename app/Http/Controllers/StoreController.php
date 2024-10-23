<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    // Get all stores
    public function index()
    {
        return Store::all();
    }

    // Create new store
    public function store(Request $request)
    {
        $request->validate([
            'nama_usaha' => 'required|string',
            'jenis_usaha' => 'required|string',
            'alamat' => 'required|string',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($request->hasFile('gambar')){
            $path = $request->file('gambar')->store('public/images');
            $gambar = basename($path);
        } else {
            $gambar = null;
        }

        return Store::create([
            'nama_usaha' => $request->nama_usaha,
            'jenis_usaha' => $request->jenis_usaha,
            'alamat' => $request->alamat,
            'gambar' => $gambar,
        ]);
    }

    // Update existing store
    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $request->validate([
            'nama_usaha' => 'required|string',
            'jenis_usaha' => 'required|string',
            'alamat' => 'required|string',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($request->hasFile('gambar')){
            // Delete old image if exists
            if ($store->gambar) {
                Storage::delete('public/images/'.$store->gambar);
            }
            $path = $request->file('gambar')->store('public/images');
            $store->gambar = basename($path);
        }

        $store->nama_usaha = $request->nama_usaha;
        $store->jenis_usaha = $request->jenis_usaha;
        $store->alamat = $request->alamat;

        $store->save();

        return $store;
    }

    // Delete a store
    public function destroy($id)
    {
        $store = Store::findOrFail($id);

        // Delete image if exists
        if ($store->gambar) {
            Storage::delete('public/images/'.$store->gambar);
        }

        $store->delete();

        return response()->json(['message' => 'Store deleted successfully']);
    }

    // Get single store
    public function show($id)
    {
        return Store::findOrFail($id);
    }
}
