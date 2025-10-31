<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Menampilkan semua service
    public function index(Request $request)
{
    if ($request->boolean('all')) {
        $services = Service::orderBy('id', 'desc')->get();
    } else {
        $services = Service::orderBy('id', 'desc')->paginate(5);
    }

    return response()->json($services);
}




    // Menampilkan detail service
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    // Menambahkan service baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:20', // disesuaikan dengan migration
            'price' => 'required|numeric|min:0',
            'type'  => 'required|in:kg,item',
        ]);

        $service = Service::create($validated);
        return response()->json([
            'message' => 'Service berhasil dibuat',
            'data' => $service
        ], 201);
    }

    // Update service
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'  => 'sometimes|required|string|max:20',
            'price' => 'sometimes|required|numeric|min:0',
            'type'  => 'sometimes|required|in:kg,item',
        ]);

        $service = Service::findOrFail($id);
        $service->update($validated);

        return response()->json([
            'message' => 'Service berhasil diupdate',
            'data' => $service
        ]);
    }

    // Hapus service
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json([
            'message' => 'Service berhasil dihapus'
        ]);
    }
}