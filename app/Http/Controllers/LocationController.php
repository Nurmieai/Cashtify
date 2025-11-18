<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        // Menampilkan alamat milik user yang sedang login (pembeli)
        $locations = Location::where('lcn_user_id', Auth::id())
                             ->latest('lcn_created_at')
                             ->get();

        return view('location.index', compact('locations'));
    }

    public function create()
    {
        return view('location.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'lcn_name'      => 'required|string|max:255',    // nama alamat (ex: rumah, kos, kantor)
            'lcn_address'   => 'required|string|max:500',    // alamat lengkap
            'lcn_latitude'  => 'nullable|numeric',
            'lcn_longitude' => 'nullable|numeric',
        ]);

        Location::create([
            'lcn_user_id'    => Auth::id(),
            'lcn_name'       => $request->lcn_name,
            'lcn_address'    => $request->lcn_address,
            'lcn_latitude'   => $request->lcn_latitude,
            'lcn_longitude'  => $request->lcn_longitude,
            'lcn_created_by' => Auth::id(),
        ]);

        return redirect()->route('location.index')
                         ->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Pastikan user hanya bisa edit alamat miliknya
        $location = Location::where('lcn_user_id', Auth::id())
                            ->findOrFail($id);

        return view('location.edit', compact('location'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lcn_name'      => 'required|string|max:255',
            'lcn_address'   => 'required|string|max:500',
            'lcn_latitude'  => 'nullable|numeric',
            'lcn_longitude' => 'nullable|numeric',
        ]);

        $location = Location::where('lcn_user_id', Auth::id())
                            ->findOrFail($id);

        $location->update([
            'lcn_name'       => $request->lcn_name,
            'lcn_address'    => $request->lcn_address,
            'lcn_latitude'   => $request->lcn_latitude,
            'lcn_longitude'  => $request->lcn_longitude,
            'lcn_updated_by' => Auth::id(),
        ]);

        return redirect()->route('location.index')
                         ->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $location = Location::where('lcn_user_id', Auth::id())
                            ->findOrFail($id);

        $location->lcn_deleted_by = Auth::id();
        $location->save();

        $location->delete();

        return redirect()->route('location.index')
                         ->with('success', 'Alamat berhasil dihapus.');
    }
}
