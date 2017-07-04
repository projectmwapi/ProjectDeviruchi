<?php

namespace App\Modules\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryTruck extends Model
{

    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'delivery_truck_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'delivery_truck';
    
    /**
     * Disable timestamps
     * @var  string
     */
    public $timestamps = false;

}
