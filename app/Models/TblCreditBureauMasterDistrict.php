<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblCreditBureauMasterDistrict extends Model
{
    use HasFactory;

    protected $table = 'tbl_credit_bureau_master_districts';
    protected $fillable = [
        'id',
        'Month',
        'Quarter',
        'State',
        'District',
        'jsonData',
    ];
}
