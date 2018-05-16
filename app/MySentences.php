<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MySentences extends Model
{
    protected $table = "mysentences";
     public function user_type(){
    	return $this->belongsTo('App\User','id_user','id');
    }
}
