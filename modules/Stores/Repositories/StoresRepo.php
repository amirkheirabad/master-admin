<?php

namespace Modules\Stores\Repositories;

use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;
use Modules\Stores\Models\CheckList;
use Modules\Stores\Models\Stores;
use Modules\User\Models\User;

class StoresRepo implements InterfaceStores
{
    public function getAll($siteType = null)
    {
        return Stores::when($siteType, fn ($query) => $query->where('site_type', $siteType))
            ->orderBy('created_at', 'desc')->get();
    }

    public function getUsers(){
        return  User::orderBy('created_at', 'desc')->get();
    }

    public function index()
    {
        return Stores::with(['user', 'projectManager'])->orderBy('created_at', 'desc')->paginate(10);
    }

    public function filterStores(Request $request, $siteType = 'index')
    {
        $searchQuery = $request->input('search_query');

        return Stores::query()
            ->with(['user', 'projectManager'])
            ->where('site_type', $siteType)
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
            ->when($request->filled('project_manager_id'), function ($q) use ($request) {
                $q->where('project_manager_id', $request->project_manager_id);
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
            'project_manager_id' => $data['project_manager_id'] ?? null,
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
            'contract_date' => ($data['contract_date'] ?? null) ? Verta::parse($data['contract_date'])->toCarbon() : null,
            'delivery_date' => ($data['delivery_date'] ?? null) ? Verta::parse($data['delivery_date'])->toCarbon() : null,
            'site_type' => $data['site_type'],
            'is_active' => $data['is_active'],
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
            'project_manager_id' => $request->project_manager_id,
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
            'contract_date' => $request->contract_date ? Verta::parse($request->contract_date)->toCarbon() : null,
            'delivery_date' => $request->delivery_date ? Verta::parse($request->delivery_date)->toCarbon() : null,
            'site_type' => $request->site_type,
            'is_active' => $request->is_active,
        ];

        if ($request->hasFile('logo_path')) {
            $data['logo_path'] = $request->logo_path->store('logos', 'public');
        }

        $store = Stores::find($id);
        $store->update($data);
        $incompatibleCheckLists = $store->checkLists()
            ->where('check_lists.site_type', '!=', $store->site_type)
            ->pluck('check_lists.id');
        $store->checkLists()->detach($incompatibleCheckLists);

        return $store->refresh();
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
       return Stores::with(['user', 'projectManager'])->findOrfail($id);
    }

    public function getCheckLists()
    {
        return CheckList::latest()->paginate(10);
    }

    public function getAllCheckLists($siteType)
    {
        return CheckList::where('site_type', $siteType)->get();
    }

    public function createCheckList(Request $request)
    {
        CheckList::create($request->only('title', 'site_type'));
    }

    public function updateCheckList($id, $request)
    {
        $checkList = CheckList::find($id);
        $checkList->update($request->only('title', 'site_type'));
        DB::table('store_check_lists')
            ->where('check_list_id', $checkList->id)
            ->whereIn('store_id', Stores::where('site_type', '!=', $checkList->site_type)->select('id'))
            ->delete();
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

        DB::transaction(function () use ($request, $store) {
            $store->update(['report' => $request->input('report')]);
            $store->checkLists()->sync($request->check_lists ?? []);

            $comments = collect($request->input('comments', []));
            $validCheckListIds = CheckList::whereKey($comments->keys())->pluck('id')->flip();

            $comments->each(function ($comment, $checkListId) use ($store, $validCheckListIds) {
                $checkListId = (int) $checkListId;

                if (!$validCheckListIds->has($checkListId)) {
                    return;
                }

                $comment = trim((string) $comment);

                if (!$comment) {
                    $store->checkListComments()->where('check_list_id', $checkListId)->delete();
                    return;
                }

                $store->checkListComments()->updateOrCreate(
                    ['check_list_id' => $checkListId],
                    ['comment' => $comment]
                );
            });
        });

        return $store->refresh();
    }

    public function getCheckListsStores($id)
    {
        $store = Stores::with(['checkLists', 'checkListComments'])->findOrFail($id);

        return [
            'check_lists' => $store->checkLists->pluck('id'),
            'comments' => $store->checkListComments->pluck('comment', 'check_list_id'),
            'report' => $store->report,
        ];
    }
}
