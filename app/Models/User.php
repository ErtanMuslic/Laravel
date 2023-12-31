<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Courses;
use App\Models\TestAttempt;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'gender',
        'place_of_birth',
        'country',
        'birth_date',
        'personal_number',
        'phone_number',
        'picture'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    //Relationship with courses(Professor, Which Professor created course)
    public function courses(){
        return $this->hasMany(Courses::class, 'user_id');
    }

    //Relationship with courses(Student, Show which student enrolled in which course)
    public function enrolledCourses(){
        return $this->belongsToMany(Courses::class, 'course_user' , 'user_id' , 'course_id');
    }

    //Relationship with testAttempts (Every student can take test)
    public function testAttemtps(){

        return $this->hasMany(TestAttempt::class, 'user_id');
    }

}
