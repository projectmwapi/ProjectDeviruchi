<?php

namespace App\Modules\Material\Models;

use Illuminate\Database\Eloquent\Model;

class PackingUnit extends Model
{

    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'packing_unit_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'packing_unit';
    
    /**
     * Disable timestamps
     * @var  string
     */
    public $timestamps = false;

}
