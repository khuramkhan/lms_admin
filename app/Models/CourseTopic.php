<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CourseTopic extends Model
{
    protected $fillable = ['topic','courseId','videoLink','pdf'];
}
