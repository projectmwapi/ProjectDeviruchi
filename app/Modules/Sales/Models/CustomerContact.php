<?php

namespace App\Modules\Sales\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerContact extends Model
{

    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'customer_contact_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'customer_contact';
    
}
