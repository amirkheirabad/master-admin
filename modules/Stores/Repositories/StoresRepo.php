<?php

namespace Modules\Stores\Repositories;
use Modules\Stores\Models\Stores;

class StoresRepo implements InterfaceStores
{
    public function getAll()
    {
        return Stores::all();
    }

    public function index()
    {
        return Stores::paginate(10);
    }

    public function create(array $data)
    {
        return Stores::create([
            'store_name' => $data['store_name'],
            'manager_name' => $data['manager_name'],
            'link' => $data['link'],
            'slogan' => $data['slogan'] ?? null,
            'phone' => $data['phone'],
            'province' => $data['province'],
            'city' => $data['city'],
            'location' => $data['location'],
            'code_posty' => $data['code_posty'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
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
            'manager_name' => $request->manager_name,
            'link' => $request->link,
            'slogan' => $request->slogan,
            'phone' => $request->phone,
            'province' => $request->province,
            'city' => $request->city,
            'location' => $request->location,
            'code_posty' => $request->code_posty,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'about' => $request->about,
            'token' => $request->token
        ]);
    }

    public function getById($id)
    {
       return Stores::findOrfail($id);
    }
}
