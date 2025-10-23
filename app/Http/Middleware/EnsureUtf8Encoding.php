<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUtf8Encoding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // If this is a JSON response, ensure UTF-8 encoding
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            try {
                $data = $response->getData();
                $cleanData = $this->ensureUtf8Encoding($data);
                $response->setData($cleanData);
            } catch (\Exception $e) {
                // Log the error but don't break the response
                \Log::error('UTF-8 Encoding Middleware Error: ' . $e->getMessage());
            }
        }

        return $response;
    }

    /**
     * Recursively ensure UTF-8 encoding for all string values in an array or object
     */
    private function ensureUtf8Encoding($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->ensureUtf8Encoding($value);
            }
        } elseif (is_object($data)) {
            $vars = get_object_vars($data);
            foreach ($vars as $key => $value) {
                $data->$key = $this->ensureUtf8Encoding($value);
            }
        } elseif (is_string($data)) {
            // Ensure proper UTF-8 encoding
            if (!mb_check_encoding($data, 'UTF-8')) {
                $data = mb_convert_encoding($data, 'UTF-8', 'auto');
            }
            return $data;
        }

        return $data;
    }
}