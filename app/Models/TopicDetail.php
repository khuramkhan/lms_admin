<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicDetail extends Model
{
    protected $fillable = ['topic_id','pdf','text','videoLink','addressUrl','name','type'];
}
