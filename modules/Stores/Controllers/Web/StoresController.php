<?php

namespace Modules\Stores\Controllers\Web;
use Illuminate\Http\Request;
use Modules\Stores\Models\Stores;
use Modules\Stores\Repositories\InterfaceStores;
use Modules\Stores\Services\EnamadService;
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
        $checkLists = $this->store->getAllCheckLists();

        return view('templates.stores.list', compact('stores', 'users', 'checkLists'));
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

    public function checkLists()
    {
        $checkLists = $this->store->getCheckLists();
        return view('templates.stores.check_lists', compact('checkLists'));
    }

    public function createCheckList(Request $request)
    {
        $this->store->createCheckList($request);
        return response()->json([
            'success' => true,
        ]);
    }

    public function updateCheckList(int $id, Request $request)
    {
        $this->store->updateCheckList($id, $request);
        return response()->json([
            'success' => true,
        ]);
    }

    public function show($id)
    {
        $checklist = $this->store->findCheckList($id);
        return response()->json($checklist);
    }

    public function deleteCheckList(int $id)
    {
        $this->store->deleteCheckList($id);
    }

    public function updateCheckListsStore(Request $request)
    {
        $this->store->updateCheckListsStore($request);
        return redirect()->route('list_stores');
    }

    public function getCheckListsStores($id)
    {
        $checkLists = $this->store->getCheckListsStores($id);
        return response()->json([
                'check_lists' => $checkLists,
            ]);
    }
}
