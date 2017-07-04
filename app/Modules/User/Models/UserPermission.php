<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
	
    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'user_permission_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'user_permission';
    
    /**
     * Disable timestamps
     * @var  string
     */
    public $timestamps = false;

}
