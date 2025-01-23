<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecountCategoriesInExpencesCommand extends Command
{
    protected $signature = 'recount:categories-in-expences';

    protected $description = 'Command description';

    public function handle(): void
    {
        $categoriesUsed = DB::table('category_expense')
            ->select('category_id', DB::raw('COUNT(*) as count'))
            ->groupBy('category_id')
            ->pluck('count', 'category_id');
        foreach ($categoriesUsed as $categoryId => $count) {
            Category::where('id', $categoryId)->update(['usage_count' => $count]);
        }
        $categories = Category::all();
        foreach ($categories as $category) {
            if ($category->parent_id !== 0) {
                Category::where('id', $category->parent_id)->increment('usage_count', $category->usage_count);
            }
        }
    }
}
