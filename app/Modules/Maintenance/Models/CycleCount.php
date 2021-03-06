<?php

namespace App\Modules\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CycleCount extends Model
{
	
    use SoftDeletes;

    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'cycle_count_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'cycle_count';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

}
