<?php

namespace Modules\Education\Repositories;

use Illuminate\Http\Request;
use Modules\Education\Models\Video;
use Modules\Education\Models\Video_category;


class EducationRepo implements InterfaceEducation
{
    public function storeVideo(array $data)
    {
        $file_path = $data['file_path']->store('videos', 'public');

        return Video::create([
            'title' => $data['title'],
            'category_id' => $data['category_id'],
            'description' => $data['description'],
            'file_path' => $file_path
        ]);
    }

    public function getAllCategories()
    {
        return Video_category::paginate(15);
    }

    public function getAllVideos()
    {
        return Video::with('category')->latest()->paginate(12);
    }

    public function getVideosByCategory($categoryId)
    {
        return Video::with('category')
            ->where('category_id', $categoryId)
            ->latest()
            ->paginate(12);
    }

    public function filterVideos(Request $request, $categoryId = null)
    {
        return Video::with('category')
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->when($request->filled('search_query'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('title', 'LIKE', '%' . $request->search_query . '%')
                          ->orWhere('description', 'LIKE', '%' . $request->search_query . '%');
                });
            })
            ->latest()
            ->paginate(12);
    }
    public function storeCategory(array $data)
    {
        return Video_category::create([
            'name' => $data['name'],
        ]);
    }

    public function deleteCategory(int $id)
    {
        Video_category::find($id)->delete();
    }

    public function findById($id)
    {
        return Video_category::findOrFail($id);
    }

    public function findByIdVideo($id)
    {
        return Video::findOrFail($id);
    }

    public function updateCategory($id, $request)
    {
        Video_category::find($id)->update(['name' => $request->name]);
    }

    public function delete($id)
    {
        Video::find($id)->delete();
    }

    public function updateVideo($id, $data)
    {

        $video = Video::find($id);

        $video->update([
            'title' => $data['title'],
            'category_id' => $data['category_id'],
            'description' => $data['description']
        ]);
    }
}
