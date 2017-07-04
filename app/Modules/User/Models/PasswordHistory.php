<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordHistory extends Model
{
	
    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'password_history_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'password_history';
    
}
