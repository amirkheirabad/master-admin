<?php

namespace Modules\Stores\Controllers\Web;
use Illuminate\Http\Request;
use Modules\Stores\Models\Stores;
use Modules\Stores\Repositories\InterfaceStores;
use Modules\Stores\Requests\IndexRequest;
class StoresController
{
    private InterfaceStores $store;
    public function __construct(InterfaceStores $store)
    {
        $this->store = $store;
    }

    public function list()
    {
        $stores = $this->store->index();
        return view('templates.stores.list', compact('stores'));
    }

    public function index()
    {
        return view('templates.stores.insert');
    }

    public function store(IndexRequest $request)
    {
        $this->store->create($request->validated());
        return response()->json([
            'success' => true,
            'redirect' => route('list_stores'),
            'message' => __('Store created successfully!')
        ]);
    }

    public function delete(int $id)
    {
        $this->store->delete($id);
    }

    public function edit($id)
    {
        $store = $this->store->getById($id);
        return view('templates.stores.edit', compact('store'));
    }

    public function update($id, IndexRequest $request)
    {
        $this->store->update($id, $request);
        return response()->json([
            'success' => true,
            'redirect' => route('list_stores'),
        ]);
    }
    public function store_info()
    {
        return view('templates.stores.store_info');
    }

}
