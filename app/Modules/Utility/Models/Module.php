<?php

namespace App\Modules\Utility\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
	
    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'module_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'module';
    
    /**
     * Disable timestamps
     * @var  string
     */
    public $timestamps = false;
    
}
