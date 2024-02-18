<?php

namespace App\Http\Controllers;

use App\Models\Exercises;
use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    use HttpResponses;
    public function index(Request $request)
    {
        $user = $request->user();

        $registeredStudents = Student::where('user_id', $user->id)->count();
        $registeredExercises = Exercises::where('user_id', $user->id)->count();
        $currentUserPlan = $user->plan->description;
        $remainingStudents = $user->plan->limit - $registeredStudents;

        return $this->response('Sucesso', Response::HTTP_OK, [
            'registered_students' => $registeredStudents,
            'registered_exercises' => $registeredExercises,
            'current_user_plan' => $currentUserPlan,
            'remaining_students' => $remainingStudents,
        ]);
    }
}
