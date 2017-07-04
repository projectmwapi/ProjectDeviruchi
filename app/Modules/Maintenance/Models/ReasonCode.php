<?php

namespace App\Modules\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;

class ReasonCode extends Model
{

    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'reason_code_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'reason_code';
    
    /**
     * Disable timestamps
     * @var  string
     */
    public $timestamps = false;

}
