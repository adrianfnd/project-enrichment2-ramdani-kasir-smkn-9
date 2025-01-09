<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $stocks = Stock::all();
        return view('admin.stocks.index', compact('stocks'));
    }

    public function create()
    {
        return view('admin.stocks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Stock::create($request->only('name', 'quantity', 'price', 'description'));

        return redirect()->route('stok.index')->with('success', 'Stok berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);

        return view('admin.stocks.edit', compact('stock'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $stock = Stock::findOrFail($id);

        $stock->update($request->only('name', 'quantity', 'price', 'description'));

        return redirect()->route('stok.index')->with('success', 'Stok berhasil diperbarui!');
    }

    public function show($id)
    {
        return view('admin.stocks.show', compact('stock'));
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);

        $stock->delete();
        return redirect()->route('stok.index')->with('success', 'Stok berhasil dihapus!');
    }
}
