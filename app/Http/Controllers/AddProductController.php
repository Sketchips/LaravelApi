<?php

namespace App\Http\Controllers;

use App\Models\AddProduct;
use Illuminate\Http\Request;

class AddproductController extends Controller
{
    // GET: Mendapatkan semua produk
    public function index()
    {
        $products = AddProduct::all();
        return response()->json($products);
    }

    // POST: Menambahkan produk baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'namaProduk' => 'required|string|max:255',
            'kodeProduk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'stok' => 'required|integer',
            'hargaJual' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'image' => 'nullable|string', // path gambar
        ]);

        $product = AddProduct::create($validatedData);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product
        ], 201);
    }

    // GET: Mendapatkan satu produk berdasarkan ID
    public function show($id)
    {
        $product = AddProduct::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json($product);
    }

    // PUT: Mengupdate produk berdasarkan ID
    public function update(Request $request, $id)
    {
        $product = AddProduct::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $validatedData = $request->validate([
            'namaProduk' => 'required|string|max:255',
            'kodeProduk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'stok' => 'required|integer',
            'hargaJual' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'image' => 'nullable|string', // path gambar
        ]);

        $product->update($validatedData);

        return response()->json([
            'message' => 'Produk berhasil diupdate',
            'data' => $product
        ]);
    }

    // DELETE: Menghapus produk berdasarkan ID
    public function destroy($id)
    {
        $product = AddProduct::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}
