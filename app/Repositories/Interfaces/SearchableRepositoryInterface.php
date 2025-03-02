<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface SearchableRepositoryInterface
{
    /**
     * 根據搜索條件獲取分頁結果
     *
     * @param string|null $search
     * @return LengthAwarePaginator
     */
    public function search(?string $search = null): LengthAwarePaginator;
} 