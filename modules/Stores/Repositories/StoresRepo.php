<?php

namespace Modules\Stores\Repositories;

use Illuminate\Http\Request;
use Modules\Stores\Models\Stores;
use Modules\User\Models\User;

class StoresRepo implements InterfaceStores
{
    public function getAll()
    {
        return Stores::orderBy('created_at', 'desc')->get();
    }
    public function getUsers(){
        return  User::orderBy('created_at', 'desc')->get();
    }

    public function index()
    {
        return Stores::with('user')->orderBy('created_at', 'desc')->paginate(10);
    }

    public function filterStores(Request $request)
    {
        $searchQuery = $request->input('search_query');

        return Stores::query()
            ->with('user')
            ->when($request->filled('search_query'), function ($q) use ($searchQuery) {
                $q->where(function ($query) use ($searchQuery) {
                    $query->where('store_name', 'LIKE', '%'.$searchQuery.'%')
                        ->orWhere('phone', 'LIKE', '%'.$searchQuery.'%')
                        ->orWhere('link', 'LIKE', '%'.$searchQuery.'%')
                        ->orWhere('province', 'LIKE', '%'.$searchQuery.'%')
                        ->orWhere('city', 'LIKE', '%'.$searchQuery.'%')
                        ->orWhere('slogan', 'LIKE', '%'.$searchQuery.'%')
                        ->orWhereHas('user', function ($userQuery) use ($searchQuery) {
                            $userQuery->where('name', 'LIKE', '%'.$searchQuery.'%')
                                ->orWhere('mobile', 'LIKE', '%'.$searchQuery.'%');
                        });
                });
            })
            ->when($request->filled('user_id'), function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            })
            ->when($request->filled('province'), function ($q) use ($request) {
                $q->where('province', 'LIKE', '%'.$request->province.'%');
            })
            ->when($request->filled('city'), function ($q) use ($request) {
                $q->where('city', 'LIKE', '%'.$request->city.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function create(array $data)
    {
        $logo_path = $data['logo_path']->store('logos', 'public');

        return Stores::create([
            'store_name' => $data['store_name'],
            'user_id' => $data['user_id'],
            'link' => $data['link'],
            'slogan' => $data['slogan'] ?? null,
            'phone' => $data['phone'],
            'province' => $data['province'],
            'city' => $data['city'],
            'location' => $data['location'],
            'code_posty' => $data['code_posty'],
            'about' => $data['about'] ?? null,
            'token' => $data['token'],
            'logo_path' => $logo_path ?? null,
        ]);
    }

    public function delete($id)
    {
        Stores::find($id)->delete();
    }

    public function update($id, $request)
    {
        $data = [
            'store_name' => $request->store_name,
            'user_id' => $request->user_id,
            'link' => $request->link,
            'slogan' => $request->slogan,
            'phone' => $request->phone,
            'province' => $request->province,
            'city' => $request->city,
            'location' => $request->location,
            'code_posty' => $request->code_posty,
            'about' => $request->about,
            'token' => $request->token,
        ];

        if ($request->hasFile('logo_path')) {
            $data['logo_path'] = $request->logo_path->store('logos', 'public');
        }

        return Stores::find($id)->update($data);
    }

    public function getById($id)
    {
       return Stores::with('user')->findOrfail($id);
    }
}
