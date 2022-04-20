<?php

namespace App\Repositories\Eloquent;

use Costa\Core\UseCases\Contracts\TransactionContract;
use Illuminate\Support\Facades\DB;

class TransactionDatabase implements TransactionContract
{
    public function __construct()
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }
}
