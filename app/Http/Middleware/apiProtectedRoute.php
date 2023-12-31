<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
USE \Tymon\JWTAuth\Facades\JWTAuth;

class apiProtectedRoute extends BaseMiddleware 
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            // Tenta autenticar o usuário com o token JWT
            $user = JWTAuth::parseToken()->authenticate();
        }catch(\Exception $e){
            // Verifica o tipo de exceção e retorna uma resposta adequada
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => 'Token expirado.']);
            }else if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => 'Token inválido.']);
            }else{
                return response()->json(['status' => 'Token não encontrado.']);
            }
        }
        // Se a autenticação for bem-sucedida, permite o acesso à rota protegida
        return $next($request);
    }
}
