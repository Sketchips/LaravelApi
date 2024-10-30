<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tiket;
use Symfony\Component\HttpFoundation\Response;

class TiketController extends Controller
{
    public function index()
    {
        $tikets = Tiket::all();
        return response()->json($tikets, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaTiket' => 'required|string|max:255',
            'stok' => 'required|integer',
            'hargaJual' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $tiket = Tiket::create([
            'namaTiket' => $request->namaTiket,
            'stok' => $request->stok,
            'hargaJual' => $request->hargaJual,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json($tiket, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $tiket = Tiket::find($id);

        if (!$tiket) {
            return response()->json(['message' => 'Tiket tidak ditemukan'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($tiket, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'namaTiket' => 'required|string|max:255',
            'stok' => 'required|integer',
            'hargaJual' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $tiket = Tiket::find($id);

        if (!$tiket) {
            return response()->json(['message' => 'Tiket tidak ditemukan'], Response::HTTP_NOT_FOUND);
        }

        $tiket->update([
            'namaTiket' => $request->namaTiket,
            'stok' => $request->stok,
            'hargaJual' => $request->hargaJual,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json($tiket, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $tiket = Tiket::find($id);

        if (!$tiket) {
            return response()->json(['message' => 'Tiket tidak ditemukan'], Response::HTTP_NOT_FOUND);
        }

        $tiket->delete();

        return response()->json(['message' => 'Tiket berhasil dihapus'], Response::HTTP_OK);
    }
}
