<?php

namespace App\Modules\Material\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
	
    use SoftDeletes;

    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'material_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'material';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

}
