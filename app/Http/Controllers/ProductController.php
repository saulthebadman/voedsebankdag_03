<?php

namespace App\Http\Controllers;

use App\Models\Leverancier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{
    public function edit(Leverancier $leverancier, Product $product)
    {
        return view('products.edit', compact('leverancier', 'product'));
    }

    public function update(Request $request, Leverancier $leverancier, Product $product)
    {
        $request->validate([
            'houdbaarheidsdatum' => 'required|date',
        ]);
        $nieuweDatum = Carbon::parse($request->houdbaarheidsdatum);
        $oudeDatum = Carbon::parse($product->houdbaarheidsdatum);
        if ($nieuweDatum->greaterThan($oudeDatum->copy()->addDays(7))) {
            return redirect()->back()->with('error', 'De houdbaarheidsdatum is niet gewijzigd. De houdbaarheidsdatum mag met maximaal 7 dagen worden verlengd');
        }
        $product->houdbaarheidsdatum = $nieuweDatum;
        $product->save();
        return redirect()->route('leveranciers.show', $leverancier)->with('success', 'De houdbaarheidsdatum is gewijzigd');
    }
}
