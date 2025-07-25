<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Redirect;

class RedirectController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'url' => 'required|url',
            'code' => 'required|string|max:10|unique:redirects,code',
        ]);

        // Create a new redirect
        $redirect = Redirect::create([
            'redirect_url' => $validatedData['url'],
            'code' => $validatedData['code'],
            'active' => true,
        ]);

        return response()->json(['message' => 'Link de redirecionamento criado com sucesso!', 'redirect' => $redirect], 201);
    }


    public function index()
    {
        $redirects = Redirect::all()->map(function ($item) {
        $item->short_url = url('/r/' . $item->code);
        return $item;
    });

        return response()->json(['redirects' => $redirects]);
    }


    public function stats($code)
    {
        $redirect = Redirect::where('code', $code)->firstOrFail();
        // $stats = [
        //     'total_accesses' => $redirect->logs()->count(),
        //     'last_access' => optional($redirect->getLastAccess())->created_at,
        // ];

        return response()->json(['redirect' => $redirect]);
    }
}
