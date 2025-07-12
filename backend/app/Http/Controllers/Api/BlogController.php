<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    // List all blogs
    public function index()
    {
        return response()->json(Blog::with(['category', 'tags', 'author'])->get());
    }

    // Show a single blog
    public function show($id)
    {
        $blog = Blog::with(['category', 'tags', 'author'])->findOrFail($id);
        return response()->json($blog);
    }

    // Create a new blog
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:blog_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'tag_ids' => 'array',
            'tag_ids.*' => 'exists:blog_tags,id',
        ]);
        $validated['author_id'] = $request->user()->id;
        $blog = Blog::create($validated);
        if (isset($validated['tag_ids'])) {
            $blog->tags()->sync($validated['tag_ids']);
        }
        return response()->json($blog->load(['category', 'tags', 'author']), 201);
    }

    // Update a blog
    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:blogs,slug,' . $id,
            'excerpt' => 'nullable|string',
            'content' => 'sometimes|required|string',
            'category_id' => 'nullable|exists:blog_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'tag_ids' => 'array',
            'tag_ids.*' => 'exists:blog_tags,id',
        ]);
        $blog->update($validated);
        if (isset($validated['tag_ids'])) {
            $blog->tags()->sync($validated['tag_ids']);
        }
        return response()->json($blog->load(['category', 'tags', 'author']));
    }

    // Delete a blog
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return response()->json(['message' => 'Blog deleted successfully.']);
    }
}
