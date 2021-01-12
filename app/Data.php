<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Data extends Model
{
    protected $fillable = ['dataID', 'state', 'district', 'gender', 'birthday', 'notificationDate', 'onsetDate', 'status', 'deleted_at', 'updated_at'];
    use SoftDeletes;
    public $timestamps = false;
}
