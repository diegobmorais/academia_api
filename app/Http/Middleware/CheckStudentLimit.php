<?php

namespace App\Http\Middleware;

use App\Models\Student;
use App\Traits\HttpResponses;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStudentLimit
{
    use HttpResponses;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // usuário que esta autenticado no momento
        $user = $request->user();

        // Verifique se existe um plano cadastrado no usuario
        if ($user && $user->plan_id) {
            // Obtem o numero de estudantes já cadastrados
            $studentCount = Student::where('user_id', $user->id)->count();

            // Verifique se o limite foi atingido
            $planLimit = $user->plan->limit;
            
            if ($studentCount >= $planLimit) {
                return $this->response('Você atingiu o limite de estudantes para este plano', Response::HTTP_FORBIDDEN);
            }
        }

        return $next($request);
    }
}
