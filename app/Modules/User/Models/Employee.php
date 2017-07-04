<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
	
    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'employee_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'employee';
    
    /**
     * Disable timestamps
     * @var  string
     */
    public $timestamps = false;

}
