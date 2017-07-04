<?php

namespace App\Modules\Department\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentApprover extends Model
{

    /**
     * The primary key of table.
     *
     * @var string
     */
    protected $primaryKey = 'approver_id';

    /**
     * Table name
     * @var string
     */
    protected $table = 'department_approver';
    
}
