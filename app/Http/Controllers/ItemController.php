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
    // Home page: every item, newest first.
    // ?q=jacket searches name and brand, ?brand=Nike filters by brand.
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $brand = $request->query('brand');

        $items = Item::query()
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('brand', 'like', "%{$search}%");
                });
            })
            ->when($brand, fn ($q) => $q->where('brand', $brand))
            ->latest()
            ->get();

        // AJAX request (sent by the live search) gets just the results HTML
        if ($request->ajax()) {
            return view('items._results', compact('items', 'brand', 'search'));
        }

        $brands = Item::query()->distinct()->orderBy('brand')->pluck('brand');

        return view('items.index', compact('items', 'brands', 'brand', 'search'));
    }

    // Upload form
    public function create(): View
    {
        return view('items.create');
    }

    // Save the upload
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'name'  => ['required', 'string', 'max:80'],
            'brand' => ['required', 'string', 'max:60'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999'],
        ]);

        // Save the photo straight into public/uploads so it works on mi-linux
        // without needing `php artisan storage:link`.
        $file = $request->file('photo');
        $filename = Str::random(20).'.'.$file->extension();

        File::ensureDirectoryExists(public_path('uploads'));
        $file->move(public_path('uploads'), $filename);

        $data['photo'] = 'uploads/'.$filename;

        Item::create($data);

        return redirect()->route('items.index')->with('status', 'Your item is up.');
    }
}
