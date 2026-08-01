<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index() { return view('admin.quotes.index', ['quotes' => QuoteRequest::latest()->paginate(20)]); }
    public function show(QuoteRequest $quote) { return view('admin.quotes.show', compact('quote')); }
    public function update(Request $request, QuoteRequest $quote)
    {
        $quote->update($request->validate(['status' => ['required', 'in:Nouveau,Assigné,En cours,Proposition envoyée,Clôturé'], 'notes' => ['nullable', 'string']]));
        return back()->with('success', 'Demande mise à jour.');
    }
}
