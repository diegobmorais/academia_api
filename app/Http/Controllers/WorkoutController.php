<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkoutController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'student_id' => 'required|integer',
                'exercise_id' => 'required|integer',
                'repetitions' => 'required|integer',
                'weight' => 'required|numeric',
                'break_time' => 'required|integer',
                'day' => 'required|in:SEGUNDA,TERÇA,QUARTA,QUINTA,SEXTA,SÁBADO,DOMINGO',
                'observations' => 'nullable|string',
                'time' => 'required|integer',
            ]);
            $user = auth()->user(); // Obtém o usuário autenticado
            // Verifica se o estudante pertence ao usuário logado
            $student = $user->students()->find($request->student_id);
            

            if (!$student) {
                return $this->response('Estudante não registrado para este usuario.', Response::HTTP_BAD_REQUEST);
            }

            // Verifica se já existe um treino para o mesmo dia.
            $existingWorkout = $student->workouts()
                ->where('exercise_id', $request->exercise_id)
                ->where('day', $request->day)
                ->first();

            if ($existingWorkout) {
                return $this->response('Treino já registrado para este dia.', Response::HTTP_CONFLICT);
            }

            // Cria o treino
            $workout = Workout::create($request->all());


            return $this->response('Cadastrado com sucesso.', Response::HTTP_CREATED, $workout,);
        } catch (\Exception $e) {
            return $this->response('Erro nos dados de atualização', Response::HTTP_BAD_REQUEST);
        }
    }

}
