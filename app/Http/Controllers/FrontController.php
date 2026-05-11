<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * Tampilkan halaman utama (Katalog Produk)
     */
    public function index(Request $request)
    {
        // Mulai query untuk produk yang statusnya 'published'
        $query = Product::where('status', 'published')
            ->with(['media' => function ($q) {
                $q->where('is_primary', true);
            }]);

        // FITUR PENCARIAN (Berdasarkan nama atau deskripsi)
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // FITUR FILTER
        if ($request->filled('filter')) {
            $filter = $request->filter;

            if ($filter === 'ready') {
                $query->where('stock', '>', 0);
            } elseif ($filter === 'po') {
                $query->where('stock', 0);
            } elseif ($filter === 'iphone') {
                $query->where(function($q) {
                    $q->where('name', 'like', '%iphone%')
                      ->orWhere('name', 'like', '%apple%')
                      ->orWhere('name', 'like', '%ipad%');
                });
            } elseif ($filter === 'android') {
                $query->where(function($q) {
                    $q->where('name', 'not like', '%iphone%')
                      ->where('name', 'not like', '%apple%')
                      ->where('name', 'not like', '%ipad%');
                });
            }
        }

        $products = $query->latest()->get();

        return view('front.index', compact('products'));
    }

    /**
     * Tampilkan halaman detail produk
     */
    public function show($slug)
    {
        // Cari produk berdasarkan slug, pastikan statusnya published
        $product = Product::where('slug', $slug)
            ->where('status', 'published')
            ->with('media') // Ambil semua foto/videonya
            ->firstOrFail();

        return view('front.show', compact('product'));
    }
}
