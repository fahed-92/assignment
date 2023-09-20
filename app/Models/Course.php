<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded=[];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'teacher_id'
    ];

    public function Teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function User(){
        return $this->belongsToMany(User::class , 'course_user');
    }
}
