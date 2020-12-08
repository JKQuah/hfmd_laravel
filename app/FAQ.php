<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FAQ extends Model
{
    //
    protected $table = 'faq';
    protected $fillable = ['question', 'answer'];
    use SoftDeletes;


}
