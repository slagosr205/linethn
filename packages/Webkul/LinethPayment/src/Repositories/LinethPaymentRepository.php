<?php

namespace Webkul\LinethPayment\Repositories;

use Webkul\Core\Eloquent\Repository;

class LinethPaymentRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\LinethPayment\Contracts\LinethPaymentModel';
    }
}