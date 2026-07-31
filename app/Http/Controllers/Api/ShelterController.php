<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shelter;

class ShelterController extends Controller
{
    public function index()
    {
        $shelters = Shelter::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $shelters,
        ]);
    }
}
