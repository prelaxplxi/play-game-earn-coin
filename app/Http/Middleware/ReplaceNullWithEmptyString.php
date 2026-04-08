<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReplaceNullWithEmptyString
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $data = $response->getData(true);
            $data = $this->replaceNullWithEmptyString($data);
            $response->setData($data);
        }

        return $response;
    }

    /**
     * Recursively replace null values with empty strings.
     *
     * @param  mixed  $data
     * @return mixed
     */
    protected function replaceNullWithEmptyString($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->replaceNullWithEmptyString($value);
            }
        } elseif (is_null($data)) {
            return '';
        }

        return $data;
    }
}
