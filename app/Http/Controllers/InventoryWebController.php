<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Contracts\View\View;

class InventoryWebController extends Controller
{
    public function index(): View
    {
        $products = ProductModel::all();
        return view('inventory.index', compact('products'));
    }
}