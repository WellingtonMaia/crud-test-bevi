<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Transaction
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @throws Exception|Throwable
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            DB::beginTransaction();

            $result = $next($request);

            if (isset($result) && isset($result->exception)) {
                throw $result->exception;
            }

            DB::commit();
            return $result;

        } catch (Exception $exception) {
            DB::rollBack();

            if ($exception->getCode() >= 400 && $exception->getCode() < 500 && $exception->getMessage() != '') {
                abort($exception->getCode(), $exception->getMessage());
            }

            throw $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();

            if ($throwable->getCode() >= 400 && $throwable->getCode() < 500 && $throwable->getMessage() != '') {
                abort($throwable->getCode(), $throwable->getMessage());
            }

            throw $throwable;
        }
    }
}
