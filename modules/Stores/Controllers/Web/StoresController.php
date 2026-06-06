<?php

namespace Modules\Stores\Controllers\Web;
use Illuminate\Http\Request;
use Modules\Stores\Models\Stores;
use Modules\Stores\Repositories\InterfaceStores;
use Modules\User\Repositories\InterfaceUser;
use Modules\Stores\Requests\IndexRequest;
use Modules\Stores\Requests\QuickCreateSellerRequest;

class StoresController
{
    private InterfaceStores $store;
    private InterfaceUser $user;
    public function __construct(InterfaceStores $store, InterfaceUser $user)
    {
        $this->store = $store;
        $this->user = $user;
    }

    public function list(Request $request)
    {
        $stores = $this->store->filterStores($request);
        $users = $this->store->getUsers();

        return view('templates.stores.list', compact('stores', 'users'));
    }

    public function index()
    {
        $users = $this->store->getUsers();
        return view('templates.stores.insert', compact('users'));
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
        $users = $this->user->getUsers();
        $store = $this->store->getById($id);
        return view('templates.stores.edit', compact('store', 'users'));
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


    public function quickCreateSeller(QuickCreateSellerRequest $request)
    {
        $user = $this->user->quickCreateSeller($request->validated());
 
        return response()->json([
            'success' => true,
            'user' => [
                'id'   => $user->id,
                'name' => $user->name,
            ],
        ]);
    }

}
