<?php

namespace App\Modules\Utility\Models;

use Illuminate\Database\Eloquent\Model;

use App\Modules\User\Models\Employee;

class ResourceLog extends Model
{

    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'resource_log_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'resource_log';
    
    /**
     * Disable timestamps
     * @var  string
     */
    public $timestamps = false;

    /**
     * Custom appended values that are not in table
     * @var array
     */
    protected $appends = ['created_by_name', 'modified_by_name', 'deleted_by_name'];

    /**
     * Set created_by_name index of custom attribute
     * @return String
     */
    public function getCreatedByNameAttribute()
    {
        $user_model = Employee::whereUserId($this->created_by)->first();
        return $this->attributes['created_by_name'] = ($user_model ? $user_model->first_name.' '.$user_model->last_name : '');
    }

    /**
     * Set modified_by_name index of custom attribute
     * @return String
     */
    public function getModifiedByNameAttribute()
    {
        $user_model = Employee::whereUserId($this->modified_by)->first();
        return $this->attributes['modified_by_name'] = ($user_model ? $user_model->first_name.' '.$user_model->last_name : '');
    }

    /**
     * Set deleted_by_name index of custom attribute
     * @return String
     */
    public function getDeletedByNameAttribute()
    {
        $user_model = Employee::whereUserId($this->deleted_by)->first();
        return $this->attributes['deleted_by_name'] = ($user_model ? $user_model->first_name.' '.$user_model->last_name : '');
    }
    
}
