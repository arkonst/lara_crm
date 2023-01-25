<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'company_id',
        'phone'
    ];

    protected $appends = ['companyName'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getCompanyNameAttribute()
    {
        return $this->company['name'];
    }
}
