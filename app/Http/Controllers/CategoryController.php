<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::all()->sortBy('sort')->sortBy('parent_id');


        // Build a tree structure
        $categoryTree = $this->buildTree($categories);

        $fields = CategoryResource::getFields('show');


        return Inertia::render('Categories/Index', [
            'categories' => $categoryTree,
            'fields' => $fields,
            'status' => ''
        ]);
    }

    /**
     * Recursive function to build a tree structure from categories.
     */
    private function buildTree($categories, $parentId = 0)
    {
        return $categories
            ->where('parent_id', $parentId) // Filter categories by parent_id
            ->map(function ($category) use ($categories) {
                $children = $this->buildTree($categories, $category->id); // Get children recursively
                return [
                    'id' => $category->id,
                    'title' => $category->title,
                    'slug' => $category->slug,
                    'sort' => $category->sort,
                    'parent_id' => $category->parent_id,
                    'children_count' => count($children),
                    'usage_count' => $category->usage_count,
                    'children' => $children, // Recursive call
                ];
            })
            ->values()
            ->toArray(); // Convert to array at the end if needed
    }

    public function edit(Category $category): Response
    {
        $this->authorize('update', $category);
        $fields = CategoryResource::getFields('edit');
        $category->parent_id = Category::find($category->parent_id);
        return Inertia::render('Categories/Edit', [
            'category' => $category,
            'fields' => $fields,
        ]);

    }

    public function updateOrder(Request $request): RedirectResponse
    {
        $this->authorize('view-any', Category::class);
        $categories = $request->categories;
        foreach ($categories as $key => $parentCategory) {
            $category = Category::findOrFail($parentCategory['id']);
            $category->sort = $key;
            $category->parent_id = 0;
            $category->save();
            if ($parentCategory['children']) {
                foreach ($parentCategory['children'] as $key2 => $child) {
                    $subCategory = Category::findOrFail($child['id']);
                    $subCategory->sort = (int)('1' . $key . $key2);
                    $subCategory->parent_id = $category->id;
                    $subCategory->save();
                }
            }
        }

        return redirect()->back()->with('status', 'Categories order updated.');
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $this->authorize('update', $category);


        $category->update($request->validated());

        $category->parent_id = $request->parent_id['id'] ?? 0;
        $category->save();

        return redirect()->route('categories.index')->with('status', 'Category updated.');
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->authorize('create', Category::class);
        $category = Category::create($request->validated());
        if ($request->parent_id) {
            $category->parent_id = $request->parent_id;
        } else {
            $category->parent_id = 0;
        }
        return redirect()->route('categories.index')->with('status', 'Category created.');
    }

    public function create(): Response
    {
        $this->authorize('create', Category::class);
        $fields = CategoryResource::getFields('edit');
        return Inertia::render('Categories/Create', [
            'fields' => $fields,
        ]);
    }

    public function show(Category $category): Category
    {
        $this->authorize('view', $category);

        return $category;
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()->route('categories.index')->with('status', 'Category deleted.');
    }
}
