<?php

namespace App\Modules\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Modules\Utility\Models\ResourceLog;

class UnitOfMeasurement extends Model
{
	
    use SoftDeletes;

    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'uom_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'unit_of_measurement';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Custom appended values that are not in table
     * @var array
     */
    protected $appends = ['resource_log'];

    /**
     * Set resource log index of custom attribute
     * @return Collection
     */
    public function getResourceLogAttribute()
    {   
        $resource_log = ResourceLog::whereResourceTable($this->getTable())->whereResourceId($this->uom_id)->first();
        return $this->attributes['resource_log'] = $resource_log;
    }

}
