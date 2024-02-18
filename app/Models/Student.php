<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "students";
    protected $fillable = [
        'name',
        'email',
        'date_birth',
        'cpf',
        'contact',
        'user_id',
        'city',
        'neighborhood',
        'number',
        'street',
        'state',
        'cep',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }
}
