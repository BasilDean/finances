<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateCategorySlugsCommand extends Command
{
    protected $signature = 'generate:category-slugs';

    protected $description = 'Generate slug for all existing categories without a slug.';

    public function handle(): void
    {
        // Get all categories where slug is null or empty
        $categories = Category::whereNull('slug')->orWhere('slug', '')->get();

        if ($categories->isEmpty()) {
            $this->info('All categories already have slugs.');
            return;
        }


        foreach ($categories as $category) {
            // Generate slug from the 'title' field
            $baseSlug = Str::slug($category->title);
            $category->slug = $this->generateUniqueSlug($baseSlug);

            // Save the updated category
            $category->save();

            $this->info("Generated slug for category: {$category->title} -> {$category->slug}");
        }

        $this->info('Slug generation completed for all categories.');
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
}
