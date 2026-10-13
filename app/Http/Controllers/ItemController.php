<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ItemController extends Controller
{
  
    public function index(Request $request): View
    {
        $brand = $request->query('brand');

        $items = Item::query()
            ->when($brand, fn ($q) => $q->where('brand', $brand))
            ->latest()
            ->get();

        $brands = Item::query()->distinct()->orderBy('brand')->pluck('brand');

        return view('items.index', compact('items', 'brands', 'brand'));
    }

    
    public function create(): View
    {
        return view('items.create');
    }

    
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'name'  => ['required', 'string', 'max:80'],
            'brand' => ['required', 'string', 'max:60'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999'],
        ]);


        $file = $request->file('photo');
        $filename = Str::random(20).'.'.$file->extension();

        File::ensureDirectoryExists(public_path('uploads'));
        $file->move(public_path('uploads'), $filename);

        $data['photo'] = 'uploads/'.$filename;

        Item::create($data);

        return redirect()->route('items.index')->with('status', 'Your item is up.');
    }
}
