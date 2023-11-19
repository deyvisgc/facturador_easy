<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class WarehouseDocuments extends ModelTenant
{
    protected $table = 'warehouse_documents';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'numberDocument',
        'name',
        'address',
        'condition',
        'department_id',
        'district_id',
        'location_id',
        'province_id',
        'state',
        'trade_name'
    ];
}
