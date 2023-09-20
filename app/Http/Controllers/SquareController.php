<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Square\SquareClient;
use Square\Environment;
use Square\Exceptions\ApiException;

class SquareController extends Controller
{
    public function index()
    {
        $client = new SquareClient([
            'accessToken' => env('SQUARE_ACCESS_TOKEN'),
            'environment' => Environment::SANDBOX
        ]);

        $api_response = $client->getLocationsApi()->listLocations();

        if ($api_response->isSuccess()) {
            $locations = $api_response->getResult();
            return response()->json($locations->getLocations(), 200);
            return view('square', compact('locations'));
        } else {
            $errors = $api_response->getErrors();
            return response()->json($errors, 200);
            return view('square', compact('errors'));
        }
    }
}
