<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TagRepository implements TagRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAllTags(): Collection
    {
        return Tag::orderBy('name')->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getPopularTags(int $limit = 10): Collection
    {
        return Tag::where('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Tag
    {
        return Tag::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findByName(string $name): ?Tag
    {
        return Tag::where('name', $name)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function findBySlug(string $slug): ?Tag
    {
        return Tag::where('slug', $slug)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Tag
    {
        return Tag::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): Tag
    {
        $tag = Tag::findOrFail($id);
        $tag->update($data);
        return $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        return (bool) Tag::destroy($id);
    }
} 