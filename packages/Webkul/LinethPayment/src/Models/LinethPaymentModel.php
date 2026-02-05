<?php

namespace Webkul\LinethPayment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\LinethPayment\Contracts\LinethPaymentModel as LinethPaymentModelContract;

class LinethPaymentModel extends Model implements LinethPaymentModelContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected   $table = 'lineth_payments_module';
    protected $primaryKey = 'id';
    protected $fillable = [
        // Add your fillable attributes here
        'name',
        'button_code',
        'sort_order',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // Add your attribute casts here
    ];
}