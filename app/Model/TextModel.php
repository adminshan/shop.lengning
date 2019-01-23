<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class   TextModel extends Model
{
    //
    public $table = 'p_users';
    public $timestamps = true;
    public $updated_at = false;
    protected $primaryKey = 'uid';

}
