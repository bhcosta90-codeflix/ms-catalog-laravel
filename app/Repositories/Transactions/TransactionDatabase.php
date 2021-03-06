<?php

namespace App\Repositories\Transactions;

use Costa\Core\Utils\Contracts\TransactionInterface;
use Illuminate\Support\Facades\DB;

class TransactionDatabase implements TransactionInterface
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
