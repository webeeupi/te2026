<?php

namespace App\Models\ST;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ActivityTeacher extends Pivot
{
    //
    protected $fillable = [];
    protected $guarded = [];
    protected $table = 'fetnet_activity_teachers';
}
