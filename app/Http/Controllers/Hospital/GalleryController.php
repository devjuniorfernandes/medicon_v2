<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $hospital = auth()->user()->hospital;
        $galleries = $hospital->galleries;
        return view('hospital.gallery.index', compact('galleries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'caption' => 'nullable|string|max:255',
        ]);

        $hospital = auth()->user()->hospital;
        
        $path = $request->file('image')->store('galleries', 'public');

        Gallery::create([
            'hospital_id' => $hospital->id,
            'image' => $path,
            'caption' => $request->caption,
        ]);

        return redirect()->route('hospital.gallery.index')->with('success', 'Imagem adicionada à galeria.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->hospital_id !== auth()->user()->hospital->id) {
            abort(403);
        }

        Storage::disk('public')->delete($gallery->image);
        $gallery->delete();

        return redirect()->route('hospital.gallery.index')->with('success', 'Imagem removida da galeria.');
    }
}
