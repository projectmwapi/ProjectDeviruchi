<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Modules\Utility\Models\ResourceLog;

class User extends Model
{
	
    use SoftDeletes;

    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Custom appended values that are not in table
     * @var array
     */
    protected $appends = ['resource_log'];

    /**
     * 1:1 relationship to Employee model
     * @return Collection 
     */
    public function employee()
    {
        return $this->hasOne('App\Modules\User\Models\Employee', 'user_id', 'user_id');
    }

    /**
     * 1:1 relationship to UserAccess model
     * @return Collection
     */
    public function userAccess()
    {
        return $this->hasOne('App\Modules\User\Models\UserAccess', 'user_id', 'user_id');
    }

    /**
     * 1:1 relationship to ResourceLog model
     * @return Collection
     */
    public function resourceLog()
    {
        return $this->hasOne('App\Modules\User\Models\UserAccess', 'user_id', 'user_id');
    }

    /**
     * Set resource log index of custom attribute
     * @return Collection
     */
    public function getResourceLogAttribute()
    {   
        $resource_log = ResourceLog::whereResourceTable($this->getTable())->whereResourceId($this->user_id)->first();
        return $this->attributes['resource_log'] = $resource_log;
    }

}
