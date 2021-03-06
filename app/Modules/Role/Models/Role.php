<?php

namespace App\Modules\Role\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    
    use SoftDeletes;

    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'role_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'role';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

}
