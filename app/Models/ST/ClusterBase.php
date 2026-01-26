<?php

namespace App\Models\ST;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClusterBase extends Model
{
    use HasFactory;
    protected $fillable = [];
    protected $guarded = [];
    protected $table = 'st_cluster_base';
}
