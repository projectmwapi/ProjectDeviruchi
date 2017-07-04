<?php

namespace App\Modules\Utility\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleGroup extends Model
{

    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'group_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'module_group';
    
    /**
     * Disable timestamps
     * @var  string
     */
    public $timestamps = false;
    
}
