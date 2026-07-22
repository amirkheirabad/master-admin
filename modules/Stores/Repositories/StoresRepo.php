<?php

namespace Modules\Stores\Repositories;

use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Modules\Stores\Models\CheckList;
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
        $logo_path = null;
        if (isset($data['logo_path'])) {
            $logo_path = $data['logo_path']->store('logos', 'public');
        }

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
            'enamd_expiration_date' => $data['enamd_expiration_date'] ? Verta::parse($data['enamd_expiration_date'])->toCarbon() : null,
            'domain_expiration_date' => $data['domain_expiration_date'] ? Verta::parse($data['domain_expiration_date'])->toCarbon() : null,
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
            'enamd_expiration_date'=> $request->enamd_expiration_date ? Verta::parse($request->enamd_expiration_date)->toCarbon() : null,
            'domain_expiration_date'=> $request->domain_expiration_date ? Verta::parse($request->domain_expiration_date)->toCarbon() : null,
        ];

        if ($request->hasFile('logo_path')) {
            $data['logo_path'] = $request->logo_path->store('logos', 'public');
        }

        return Stores::find($id)->update($data);
    }

    private function resolveRecipientContact(Ticket $ticket)
    {
        $store = Stores::with('user')->find($ticket->store_id);
        if ($store) {
            $phone = $store->phone ?? $store->user?->mobile;
            if ($phone) {
                return [
                    'phone' => $phone,
                    'name'  => $store->store_name ?? $store->user?->name ?? 'فروشگاه',
                ];
            }
        }

        $user = User::find($ticket->user_id);
        if ($user?->mobile) {
            return [
                'phone' => $user->mobile,
                'name'  => $user->name ?? 'کاربر',
            ];
        }

        return null;
    }

    public function getById($id)
    {
       return Stores::with('user')->findOrfail($id);
    }

    public function getCheckLists()
    {
        return CheckList::latest()->paginate(10);
    }

    public function getAllCheckLists()
    {
        return CheckList::all();
    }

    public function createCheckList(Request $request)
    {
        CheckList::create(['title' => $request->title]);
    }

    public function updateCheckList($id, $request)
    {
        CheckList::find($id)->update(['title' => $request->title]);
    }

    public function findCheckList($id)
    {
        return CheckList::findOrFail($id);
    }

    public function deleteCheckList($id)
    {
        CheckList::find($id)->delete();
    }

    public function updateCheckListsStore($request)
    {
        $store = Stores::findOrFail($request->store_id);
        $store->checkLists()->sync($request->check_lists ?? []);
    }

    public function getCheckListsStores($id)
    {
        $store = Stores::with('checkLists')->findOrFail($id);
        return $store->checkLists()->pluck('check_lists.id');
    }
}
