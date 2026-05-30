<?php

namespace Modules\Stores\Repositories;
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
        return Stores::with('user')->paginate(10);
    }

    public function create(array $data)
    {
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
        ]);
    }

    public function delete($id)
    {
        Stores::find($id)->delete();
    }

    public function update($id, $request)
    {
        Stores::find($id)->update([
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
            'token' => $request->token
        ]);
    }

    public function getById($id)
    {
       return Stores::with('user')->findOrfail($id);
    }
}
