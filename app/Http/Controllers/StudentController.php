<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Traits\HttpResponses;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    use HttpResponses;
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students|max:255',
                'date_birth' => 'required|date',
                'cpf' => 'required|string|unique:students|max:255',
                'contact' => 'required|string|unique:students|max:20',
                'cep' => 'string|nullable',
                'street' => 'string|nullable',
                'State' => 'string|nullable',
                'neighborhood' => 'string|nullable',
                'city' => 'string|nullable',
                'complement' => 'string|nullable',
                'number' => 'string|nullable',
            ],
            [
                'email.unique' => 'Este Email já esta sendo utilizado.',
                'cpf.unique' => 'Este CPF já esta sendo utilizado.',
                'contact.unique' => 'Este contato já esta sendo utilizado.',
            ]);

            $user_id = Auth::id(); // Obtém o ID do usuário logado

            if (!$user_id) {
                // Se não houver usuário autenticado, retorne um erro
                return $this->response('Usuario nao autenticado', Response::HTTP_BAD_REQUEST);
            }
            $student = Student::create($request->all());

            return $this->response('Aluno Cadastrado com sucesso', Response::HTTP_OK, ['student' => $student]);
        } catch (\Exception $e) {
            return $this->response('Erro nos dados cadastrados', Response::HTTP_BAD_REQUEST, ['erro'=> $e->getMessage()]);
        }
    }

    public function index(Request $request)
    {

        $user = $request->user();

        if (!$user) {
            return $this->response('Usuário não autenticado', Response::HTTP_UNAUTHORIZED);
        }

        $students = $user->students()->orderBy('name')->get();

        return $this->response('Lista de estudantes registrados', Response::HTTP_OK, ['Estudantes' => $students]);
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return $this->response('Estudante não encontrado na base de dados', Response::HTTP_NOT_FOUND);
        }

        // Verifica se o estudante pertence ao usuário autenticado
        if ($student->user_id !== auth()->user()->id) {
            return $this->response('Você não tem permissão para excluir este estudante', Response::HTTP_FORBIDDEN);
        }

        // Executa a deleção soft
        $student->delete();

        return $this->response('Estudante deletado com sucesso!', Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students',
                'date_birth' => 'required|date',
                'cpf' => 'required|string|unique:students',
                'contact' => 'required|string|unique:students',
                'cep' => 'string|nullable',
                'street' => 'string|nullable',
                'state' => 'string|nullable',
                'neighborhood' => 'string|nullable',
                'city' => 'string|nullable',
                'complement' => 'string|nullable',
                'number' => 'string|nullable',
            ]);

            $student = Student::find($id);

            if (!$student) {
                return $this->response('Estudante não encontrado', Response::HTTP_NOT_FOUND);
            }

            // Verifica se o estudante pertence ao usuário autenticado
            if ($student->user_id !== auth()->user()->id) {
                return $this->response('Voce nao tem permissao para modificar este estudante', Response::HTTP_FORBIDDEN);
            }

            // Atualize os dados do estudante
            $student->update($request->all());

            return $this->response('Estudante Atualizado com sucesso!', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->response('Erro nos dados de atualização', Response::HTTP_BAD_REQUEST);
        }
    }
    public function indexByStudent($id)
    {

        try {
            $user = Auth::user();
            // Obtém os estudantes associados ao usuário
            $student = $user->students()->find($id);

            if (!$student) {
                return $this->response('Estudante nao encontrado .', Response::HTTP_CONFLICT);
            }

            // Obtém informações sobre o estudante
            $studentInfo = [
                'student_id' => $student->id,
                'student_name' => $student->name,
            ];

            // Obtém todos os treinos do estudante ordenados por dia da semana
            $workouts = $student->workouts()->orderBy('day')->get();

            // Agrupa os treinos por dia da semana
            $groupedWorkouts = $workouts->groupBy('day');

            // formato do response
            $response = [
                'student_id' => $studentInfo['student_id'],
                'student_name' => $studentInfo['student_name'],
                'workout' => [
                    'segunda' => $groupedWorkouts['SEGUNDA'] ?? [],
                    'terça' => $groupedWorkouts['TERÇA'] ?? [],
                    'quarta' => $groupedWorkouts['QUARTA'] ?? [],
                    'quinta' => $groupedWorkouts['QUINTA'] ?? [],
                    'sexta' => $groupedWorkouts['SEXTA'] ?? [],
                    'sabado' => $groupedWorkouts['SÁBADO'] ?? [],
                    'domingo' => $groupedWorkouts['DOMINGO'] ?? [],
                ],
            ];

            return $this->response('Treinos do estudante', Response::HTTP_OK, $response,);
        } catch (\Exception $e) {
            return $this->response('Erro na obtenção dos dados', Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    { {
            try {
                $user = Auth::user();
                // Obtém os estudantes associados ao usuário
                $student = $user->students()->find($id);

                $response = [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'date_birth' => $student->date_birth,
                    'contact' => $student->contact,
                    'address' => [
                        'cep' => $student->cep,
                        'street' => $student->street,
                        'state' => $student->state,
                        'neighborhood' => $student->neighborhood,
                        'city' => $student->city,
                        'complement' => $student->complement,
                        'number' => $student->number,
                    ],
                ];

                return $this->response('Dados do estudante', Response::HTTP_OK, $response);
            } catch (\Exception $e) {
                return $this->response('Estudante não encontrado', Response::HTTP_NOT_FOUND);
            }
        }
    }
    // exportação de pdf para os treinos do estudante
    public function exportPdfWorkout(Request $request)
    {

        $user = Auth::user();

        $id = $request->input('id');
        // Obtém os estudantes associados ao usuário
        $student = $user->students()->find($id);

        //obtem informações sobre o estudante
        $studentInfo = [
            'ID' => $student->id,
            'Nome' => $student->name,
        ];
        // Obtém todos os treinos do estudante ordenados por dia da semana
        $workouts = $student->workouts()->with('exercise')->orderBy('day')->get();

        // Agrupa os treinos por dia da semana
        $groupedWorkouts = $workouts->groupBy('day');

        // formato do response
        $response = [
            'ID' => $studentInfo['ID'],
            'Nome' => $studentInfo['Nome'],
            'workout' => [
                'segunda' => $groupedWorkouts['SEGUNDA'] ?? [],
                'terça' => $groupedWorkouts['TERÇA'] ?? [],
                'quarta' => $groupedWorkouts['QUARTA'] ?? [],
                'quinta' => $groupedWorkouts['QUINTA'] ?? [],
                'sexta' => $groupedWorkouts['SEXTA'] ?? [],
                'sabado' => $groupedWorkouts['SÁBADO'] ?? [],
                'domingo' => $groupedWorkouts['DOMINGO'] ?? [],
            ],
        ];

        $pdf = Pdf::loadView('pdfs.workoutStudent', [
            'Nome' => $student->name,
            'Treinos' => $response,
        ]);


        if ($student == null) {
            return $this->response('Aluno não encontrado para este usuário', Response::HTTP_NOT_FOUND);
        } else {
            return  $pdf->stream('workoutStudent.pdf');
        }
    }
}
