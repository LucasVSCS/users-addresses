<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $query = Address::query();

        $query->when($request->filled('street'), function ($q) use ($request) {
            $q->where('street', 'like', '%' . $request->input('street') . '%');
        });

        $addresses = $query->get();

        return $addresses->toResourceCollection();
    }
}
