<?php

namespace App\Modules\Utility\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleAction extends Model
{
	
    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'action_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'module_action';
    
    /**
     * Disable timestamps
     * @var  string
     */
    public $timestamps = false;
    
}
