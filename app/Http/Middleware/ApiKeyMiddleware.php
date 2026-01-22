<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;

class ApiKeyMiddleware
{
    public function handle($request, Closure $next)
    {
        $key = $request->header('X-API-KEY');

        if (!$key) {
            return response()->json(['message' => 'API Key missing'], 401);
        }

        $apiKey = ApiKey::where('key', $key)
            ->where('active', true)
            ->first();

        if (!$apiKey) {
            return response()->json(['message' => 'Invalid API Key'], 401);
        }

        // 🔥 Aqui está a mágica
        $request->merge([
            'company_id' => $apiKey->company_id,
        ]);

        return $next($request);
    }
}