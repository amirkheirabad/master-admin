<?php

namespace Modules\Education\Controllers\Web;

use Illuminate\Http\Request;
use Modules\Education\Requests\StoreRequest;
use Modules\Education\Requests\UpdateRequest;
use Modules\Education\Requests\StoreCategoryRequest;
use Modules\Education\Repositories\InterfaceEducation;

class EducationController
{
    private InterfaceEducation $education;
    public function __construct(InterfaceEducation $education)
    {
        $this->education = $education;
    }
    public function index(Request $request)
    {
        $videos = $this->education->filterVideos($request);
        $categories = $this->education->getAllCategories();
        return view('templates.education.list', compact('categories', 'videos'));
    }

    public function videoList()
    {
        $videos = $this->education->getAllVideos();
        return view('templates.education.video_list', compact('videos'));
    }

    public function getVideos(Request $request)
    {
        $videos = $this->education->filterVideos($request);
        return response()->json([
            'success' => true,
            'videos' => $videos
        ]);
    }

    public function getVideosByCategory(Request $request, $categoryId)
    {
        $videos = $this->education->filterVideos($request, $categoryId);
        return response()->json([
            'success' => true,
            'videos' => $videos
        ]);
    }

    public function insert()
    {
        $categories = $this->education->getAllCategories();
        return view('templates.education.insert', compact('categories'));
    }

    public function indexCategory()
    {
        $categories = $this->education->getAllCategories();
        return view('templates.education.category_list', compact('categories'));

    }

    public function store(StoreRequest $request)
    {
        $this->education->storeVideo($request->validated());
        return response()->json([
            'success' => true,
            'redirect' => route('video-list'),
        ]);

    }

    public function storeCategory(StoreCategoryRequest $request)
    {
        $this->education->storeCategory($request->validated());
        return response()->json([
            'success' => true,
        ]);
    }

    public function category_delete(int $id)
    {
        $this->education->deleteCategory($id);
    }

    public function show($id)
    {
        $category = $this->education->findById($id);

        return response()->json($category);
    }

    public function update_video_category(Request $request, $id)
    {
        $this->education->updateCategory($id, $request);
        return response()->json([
            'success' => true,
        ]);
    }

    public function delete(int $id)
    {
        $this->education->delete($id);
    }

    public function edit(int $id)
    {
        $video = $this->education->findByIdVideo($id);
        $categories = $this->education->getAllCategories();
        return view('templates.education.edit', compact('video', 'categories'));
    }


    public function updateVideo($id, UpdateRequest $request)
    {
        $this->education->updateVideo($id, $request->validated());
        return response()->json([
            'success' => true,
            'redirect' => route('video-list'),
        ]);
    }
}
