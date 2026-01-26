<?php

namespace App\Models\ST;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $fillable = [];
    protected $guarded = [];
    protected $table = 'fetnet_students';

    public function group(){
        return $this->hasMany(Student::class, 'parent_id','id' )->orderBy('name');
    }
    public function sub(){
        return $this->hasMany(Student::class, 'parent_id','id' )->orderBy('name');
    }
}
