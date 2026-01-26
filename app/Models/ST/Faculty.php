<?php

namespace App\Models\ST;

use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;
    protected $fillable = [];
    protected $guarded = [];
    protected $table = 'institution_faculty';
    public function building(){
        return $this->hasMany(Building::class, 'faculty_id','id' );

    }
}
