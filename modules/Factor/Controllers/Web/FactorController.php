<?php

namespace Modules\Factor\Controllers\Web;
use Illuminate\Http\Request;
use Modules\Factor\Models\Category;
use Modules\Factor\Repositories\InterfaceFactor;
use Modules\Stores\Repositories\InterfaceStores;
use Modules\Factor\Requests\StoreFactor;
use Modules\Factor\Requests\UpdateFactorRequest;

class FactorController
{
    private InterfaceFactor $factor;
    private InterfaceStores $store;
    public function __construct(InterfaceFactor $factor, InterfaceStores $store)
    {
        $this->factor = $factor;
        $this->store = $store;
    }

    public function index(Request $request)
    {
        $stores = $this->store->getAll();
        $categories = $this->factor->getAllCategories();
        $factors = $this->factor->filterFactor($request);
        return view('templates.factor.list', compact('stores', 'categories', 'factors'));
    }

    public function insert()
    {
        $stores = $this->store->getAll();
        $categories = $this->factor->getAllCategories();
        return view('templates.factor.insert', compact('stores' , 'categories'));
    }

    public function index_category()
    {
        $categories = $this->factor->getCategory();
        return view('templates.factor.category-list', compact('categories'));
    }

    public function insert_category(Request $request)
    {
        $this->factor->createCategory($request->all());
        return redirect(route('category-list'));
    }

    public function show($id)
    {
        $category = $this->factor->findById($id);

        return response()->json($category);
    }

    public function update_category(Request $request, $id)
    {
        $this->factor->updateCategory($id, $request);
        return response()->json([
            'success' => true,
        ]);
    }

    public function category_delete(int $id)
    {
        $this->factor->deleteCategory($id);
    }

    public function factor_index()
    {
        return view('templates.factor.factor');
    }

    public function storeFactor(StoreFactor $request)
    {
        $this->factor->createFactor($request->validated());
        return response()->json([
            'success' => true,
            'redirect' => route('factor-list'),
            'message' => __('factor created successfully!')
        ]);
    }

    public function deleteFactor(int $id)
    {
        $this->factor->deleteFactor($id);
    }

    public function factor_edit($id)
    {
        $factor = $this->factor->factorById($id);
        $stores = $this->store->getAll();
        $categories = $this->factor->getAllCategories();
        return view('templates.factor.edit', compact('stores', 'categories', 'factor'));
    }

    public function updateFactor($id, UpdateFactorRequest $request)
    {
        $this->factor->updateFactor($id, $request->validated());
        return response()->json([
            'success' => true,
            'redirect' => route('factor-list'),
        ]);
    }

    public function showFactor($id)
    {
        $factor = $this->factor->factorById($id);
        return view('templates.factor.factor', compact('factor'));
    }

    public function getHash($id)
    {
        $hash = $this->factor->getHashById($id);
        return response()->json(['hash' => $hash]);
    }

}
