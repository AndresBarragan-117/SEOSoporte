<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Str;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        // --- DEBUG: filtrar rutas innecesarias (.map y .well-known) -----------------------------------------
        try {
            $path = request()->path();

            if (
                Str::contains($path, '.map') ||
                Str::contains($path, '.well-known')
            ) {
                // No reportar estos errores
                return;
            }
        } catch (\Throwable $t) {
            // En caso de que request() no esté disponible (por consola, etc.)
        }
        // --- FIN DEBUG --------------------------------------------------------------------------------------

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        // --- DEBUG: mostrar la excepción en la pantalla -----------------------------------------------------
        try {
            $path = $request->path();

            // Ignorar errores de archivos .map o .well-known
            if (
                \Illuminate\Support\Str::contains($path, '.map') ||
                \Illuminate\Support\Str::contains($path, '.well-known')
            ) {
                // No registrar este error ni mostrarlo
                return response('', 204); // Respuesta vacía sin error
            }
        } catch (\Throwable $t) {
            // Silenciar si request() no existe
        }

        // Solo loguea errores "reales"
        \Log::error("❌ Error detectado: " . $exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);
        // --- FIN DEBUG ----------------------------------------------------------------------------

        return parent::render($request, $exception);
    }

}
