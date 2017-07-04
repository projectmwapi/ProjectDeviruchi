<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
	
    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'access_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'user_access';
    
    /**
     * Disable timestamps
     * @var  string
     */
    public $timestamps = false;

}
