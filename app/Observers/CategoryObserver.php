<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryObserver
{
    public function creating(Category $category): void
    {
        $category->normalized_title = mb_strtolower($category->title);

        $category->slug = $this->generateUniqueSlug($category->title);

    }

    private function generateUniqueSlug(string $title): string
    {
        $originalSlug = Str::slug($title);
        $latestSlug = Category::withTrashed()
            ->where('slug', 'LIKE', "{$originalSlug}%")
            ->latest('id') // Get the most recent entry
            ->value('slug'); // Fetch the slug

        if ($latestSlug) {
            $number = (int)str_replace("{$originalSlug}-", '', $latestSlug) + 1;
            return "{$originalSlug}-{$number}";
        }

        return $originalSlug;
    }

    public function created(Category $category): void
    {
    }

    public function updating(Category $category): void
    {
        $category->normalized_title = mb_strtolower($category->title);
    }

    public function updated(Category $category): void
    {
    }

    public function saving(Category $category): void
    {
    }

    public function saved(Category $category): void
    {
    }

    public function deleting(Category $category): void
    {
    }

    public function deleted(Category $category): void
    {
    }

    public function restoring(Category $category): void
    {
    }

    public function restored(Category $category): void
    {
    }

    public function forceDeleting(Category $category): void
    {
    }

    public function forceDeleted(Category $category): void
    {
    }
}
