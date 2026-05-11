<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductMediaController extends Controller
{
    /**
     * Menghapus spesifik foto produk
     */
    public function destroy(ProductMedia $media)
    {
        $productId = $media->product_id;
        $isPrimary = $media->is_primary;

        // Hapus file fisik dari storage
        if (Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }

        // Hapus record dari database
        $media->delete();

        // Jika yang dihapus adalah foto utama (cover), jadikan foto lain (jika ada) sebagai cover baru
        if ($isPrimary) {
            $nextMedia = ProductMedia::where('product_id', $productId)->orderBy('sort_order')->first();
            if ($nextMedia) {
                $nextMedia->update(['is_primary' => true]);
            }
        }

        return redirect()->back()->with('success', 'Foto berhasil dihapus.');
    }
}
