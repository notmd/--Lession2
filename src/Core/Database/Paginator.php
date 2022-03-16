<?php

namespace App\Core\Database;

use App\Core\Request;
use Countable;
use Iterator;

class Paginator implements Countable, Iterator
{
    private int $currentPage;
    private int $position = 0;
    private int $lastPage;

    public function __construct(
        private array $items,
        private  int $total,
        private int $perPage,
        ?int $currentPage = null
    ) {
        $this->currentPage = $currentPage ?? Request::get('page') ?? 1;
        $this->lastPage = max((int) ceil($total / $perPage), 1);
    }

    public function total(): int
    {
        return $this->total;
    }

    public function url(int $page): string
    {
        if ($page <= 0) {
            $page = 1;
        }

        return '/' . Request::uri() . '?' . http_build_query([...Request::query(), 'page' => $page]);
    }

    public function previousPageUrl(): ?string
    {
        if ($this->currentPage() > 1) {
            return $this->url($this->currentPage() - 1);
        }

        return null;
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function nextPageUrl(): ?string
    {
        if ($this->hasMorePages()) {
            return $this->url($this->currentPage() + 1);
        }

        return null;
    }

    public function hasMorePages(): bool
    {
        return $this->currentPage() < $this->lastPage;
    }

    public function lastPage(): int
    {
        return $this->lastPage;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): mixed
    {
        return $this->items[$this->position];
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function render()
    {
        view('paginate', [
            'paginator' => $this
        ], null);
    }
}
