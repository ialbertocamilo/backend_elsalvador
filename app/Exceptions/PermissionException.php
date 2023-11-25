<?php

namespace App\Exceptions;

class PermissionException extends \Exception
{

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        // Puedes añadir lógica para registrar la excepción en el sistema de logs
    }

    /**
     * Renderizar la excepción en una respuesta HTTP.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json(['message' => $this->message], 401);
    }
}
