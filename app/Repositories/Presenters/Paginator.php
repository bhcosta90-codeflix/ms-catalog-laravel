<?php

namespace App\Repositories\Presenters;

use Costa\Core\Utils\Contracts\PaginationInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

class Paginator implements PaginationInterface
{
    protected array $data;

    public function __construct(private LengthAwarePaginator $paginator)
    {
        $this->data = $this->resolveItems(
            items: $this->paginator->items()
        );
    }

    /**
     * @return stdClass[]
     */
    public function items(): array
    {
        return $this->data;
    }

    public function total(): int
    {
        return $this->paginator->total() ?? 0;
    }

    public function lastPage(): int
    {
        return $this->paginator->lastPage() ?? 0;
    }

    public function firstPage(): int
    {
        return $this->paginator->firstItem() ?? 0;
    }

    public function currentPage(): int
    {
        return $this->paginator->currentPage() ?? 0;
    }

    public function perPage(): int
    {
        return $this->paginator->perPage() ?? 0;
    }

    public function to(): int
    {
        return $this->paginator->firstItem() ?? 0;
    }

    public function from(): int
    {
        return $this->paginator->lastItem() ?? 0;
    }

    protected function resolveItems(array $items)
    {
        $response = [];

        foreach ($items as $item) {
            $stdClass = new stdClass;

            foreach ($item->toArray() as $k => $v) {
                $stdClass->{$k} = $v;
            }

            array_push($response, $stdClass);
        }

        return $response;
    }
}
