<?php

namespace App\Http\Controllers\Proxy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WMSProxyController extends Controller
{

public function forward(Request $request)
{
    $service = strtoupper($request->query('SERVICE', ''));
    $reqType = strtolower($request->query('REQUEST', ''));
    $verType = strtolower($request->query('VERSION', ''));


    $externalUrl = $request->query('url', '');

    if (empty($externalUrl)) {
        return response()->json(['error' => 'Missing WMS URL.'], 400);
    }

    // Remove 'url' from query parameters
    $queryParams = $request->except('url');

    try {
        $response = Http::withOptions([
            'verify' => false   
        ])->withHeaders([
            'Accept' => 'application/xml',
        ])->get($externalUrl, $queryParams);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to fetch WMS URL.',
            'message' => $e->getMessage(),
        ], 500);
    }

    $res = response($response->body(), $response->status())
        ->header('Content-Type', $response->header('Content-Type') ?? 'application/xml');

    // Allow CORS only for WMS GetCapabilities
    if ($service === 'WMS' && $reqType === 'getcapabilities') {
        $res->header('Access-Control-Allow-Origin', '*');
    }

    return $res;
}



}
