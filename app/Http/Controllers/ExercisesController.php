<?php

namespace App\Http\Controllers;

use App\Models\Exercises;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ExercisesController extends Controller
{
    use HttpResponses;
    public function store(Request $request)
    {

        try {
            $request->validate([
                "description" => "required|max:255",
            ]);

            $user = $request->user();

            $existingExercise = Exercises::where('user_id', $user->id)
                ->where('description', $request->input('description'))
                ->first();

            if ($existingExercise) {
                // Retorna uma resposta de conflito se o exercício já existir
                return $this->error('Exercício já cadastrado para o mesmo usuário', Response::HTTP_CONFLICT);
            }

            $exercise = Exercises::create([
                "description" => $request->input("description"),
                "user_id" => $user->id,
            ]);
            return $this->response('Exercicio cadastrado com sucesso', Response::HTTP_OK, ['Exercises' => $exercise]);
        } catch (ValidationException $e) {
            return $this->error('Erro no cadastro, verifique os dados digitados', Response::HTTP_BAD_REQUEST);
        }
    }

    public function index(Request $request)
    {

        $user = $request->user();

        $exercises = Exercises::where('user_id', $user->id)
            ->orderBy('description')
            ->get();

        return $this->response('Exercicios cadastrados pelo usuário ' . $user->id, Response::HTTP_OK, ['exercises' => $exercises]);
    }

    public function destroy(Request $request, $id)    {

        $user = $request->user();

        $exercise = Exercises::find($id);

        // Verifica se o exercício existe
        if (!$exercise) {
            return $this->response('Exercício não encontrado', Response::HTTP_NOT_FOUND);
        }

        // Verifica se o exercício pertence ao usuário autenticado
        if ($exercise->user_id !== $user->id) {
            return $this->response('Você não possui essa permissão', Response::HTTP_NOT_FOUND);
        }

        // Deleta o exercício
        $exercise->delete();

        // Retorna resposta de sucesso
        return $this->response('Exercicio deletado com sucesso', Response::HTTP_OK);

    }
}
