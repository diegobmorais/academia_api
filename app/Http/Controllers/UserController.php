<?php

namespace App\Http\Controllers;

use App\Mail\SendMailWelcomeToUser;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use HttpResponses;
    public function store(Request $request)
    {
        try {

            $request->validate(
                [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users|max:255',
                    'date_birth' => 'required|date',
                    'cpf' => 'required|string|unique:users,cpf|max:14',
                    'password' => 'required|string|min:8|max:32',
                    'plan_id' => 'required|exists:plans,id',

                ],
                [
                    'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
                    'cpf.max' => 'O CPF deve conter no maximo 14 caracteres.',
                    'email.unique' => 'Este email já esta sendo utilizado.',
                    'cpf.unique' => 'Este CPF já esta sendo utilizado.',
                ]
            );

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'date_birth' => $request->input('date_birth'),
                'cpf' => $request->input('cpf'),
                'password' => bcrypt($request->input('password')), //bcrypt cria uma hash na senha do usuario no bd
                'plan_id' => $request->input('plan_id'),
            ]);

            // Verifica se há um plano associado ao usuário
            if ($user->plan) {
                Mail::to($user->email, $user->name)
                    ->send(new SendMailWelcomeToUser($user->name, $user->plan->description, $user->plan->limit));
            } else {
                return $this->response('Usuário não possui um plano associado', Response::HTTP_INTERNAL_SERVER_ERROR);
            }


            // Retorna os dados do usuário cadastrado caso passe pela validação
            return $this->response('Usuário cadastrado com sucesso!', Response::HTTP_OK, $user->makeHidden(['password', 'remember_token']));
        } catch (ValidationException $e) {
            $errorMessage = $e->getMessage();
            $errors = $e->errors();

            // Verifica se há um erro específico para a regra de validação do password
            if (isset($errors['password'])) {
                return $this->error($errors['password'][0], Response::HTTP_BAD_REQUEST);
            }

            // Verifica outros erros, como email e cpf
            if (isset($errors['email'])) {
                return $this->error($errors['email'][0], Response::HTTP_CONFLICT);
            } elseif (isset($errors['cpf'])) {
                return $this->error($errors['cpf'][0], Response::HTTP_CONFLICT);
            }
        }
        // Retorna um response com status 400 e mensagens de erro
        return $this->error('Erro nos dados cadastrados', Response::HTTP_BAD_REQUEST);
    }
}

