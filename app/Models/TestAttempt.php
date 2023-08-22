<?php

namespace App\Models;

use App\Models\User;
use App\Models\TestQuestions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestAttempt extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'test_attempt_number', 'score'];


    public function testQuestions()
    {
        return $this->hasMany(TestQuestions::class);
    }

}