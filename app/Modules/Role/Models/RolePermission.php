<?php

namespace App\Modules\Role\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
	
    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'role_permission_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'role_permission';
    
    /**
     * Disable timestamps
     * @var  string
     */
    public $timestamps = false;

}
