<?php

namespace App\Http\Controllers;

use App\Models\AddProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class AddProductController extends Controller
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
    // Validasi input termasuk file gambar
    $validatedData = $request->validate([
        'namaProduk' => 'required|string|max:255',
        'kodeProduk' => 'required|string|max:255',
        'kategori' => 'required|string',
        'stok' => 'required|integer',
        'hargaJual' => 'required|numeric',
        'keterangan' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
    ]);

    // Default nilai $imageName jika gambar tidak diupload
    $imageName = null;

    // Cek jika ada file gambar
    if ($request->hasFile('image')) {
        Log::info('Image file detected in the request.'); // Debugging

        try {
            // Simpan gambar ke direktori storage
            $path = $request->file('image')->store('public/images');
            $imageName = basename($path); // Nama file gambar
            Log::info('Image uploaded with name: ' . $imageName); // Debugging
        } catch (\Exception $e) {
            // Error handling jika terjadi masalah saat menyimpan gambar
            Log::error('Failed to upload image: ' . $e->getMessage()); // Debugging
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengunggah gambar',
                'error' => $e->getMessage(),
            ], 500);
        }
    } else {
        Log::info('No image file found in the request.'); // Debugging
    }

    // Buat produk baru
    $product = AddProduct::create([
        'namaProduk' => $validatedData['namaProduk'],
        'kodeProduk' => $validatedData['kodeProduk'],
        'kategori' => $validatedData['kategori'],
        'stok' => $validatedData['stok'],
        'hargaJual' => $validatedData['hargaJual'],
        'keterangan' => $validatedData['keterangan'],
        'image' => $imageName,
    ]);

    // Berikan respons berdasarkan ada tidaknya gambar
    if ($imageName) {
        // Jika gambar berhasil diupload, beri status 201 Created
        return response()->json([
            'message' => 'Produk berhasil ditambahkan dengan gambar',
            'data' => $product,
            'product_image_url' => url('storage/images/' . $imageName),
        ], 201);
    } else {
        // Jika tidak ada gambar, beri status 200 OK
        return response()->json([
            'message' => 'Produk berhasil ditambahkan tanpa gambar',
            'data' => $product,
        ], 200);
    }
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
        ]);

        // Jika ada file gambar baru, hapus gambar lama dan simpan yang baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::delete('public/images/' . $product->image);
            }

            // Simpan gambar baru
            $path = $request->file('image')->store('public/images');
            $imageName = basename($path);
        } else {
            $imageName = $product->image; // Jika tidak ada gambar baru, tetap gunakan gambar lama
        }

        // Update produk dengan data baru
        $product->update([
            'namaProduk' => $validatedData['namaProduk'],
            'kodeProduk' => $validatedData['kodeProduk'],
            'kategori' => $validatedData['kategori'],
            'stok' => $validatedData['stok'],
            'hargaJual' => $validatedData['hargaJual'],
            'keterangan' => $validatedData['keterangan'],
            'image' => $imageName, // Update nama gambar
        ]);

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

        // Hapus gambar produk jika ada
        if ($product->image) {
            Storage::delete('public/images/' . $product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}
