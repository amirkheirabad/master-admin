<?php

namespace Modules\Factor\Repositories;

use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Factor\Models\Category;
use Modules\Factor\Models\Factor;
use Modules\Stores\Models\Stores;
use Modules\Factor\Services\SmsService;

class FactorRepo implements InterfaceFactor
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function getCategory()
    {
        return Category::latest()->paginate(15);
    }

    public function createCategory(array $data)
    {
        return Category::create([
            'name' => $data['name'],
        ]);
    }

    public function findById($id)
    {
        return Category::findOrFail($id);
    }

    public function updateCategory($id, $request)
    {
        Category::find($id)->update(['name' => $request->name, 'active' => $request->active]);
    }

    public function deleteCategory(int $id)
    {
        Category::find($id)->delete();
    }

    public function filterFactor(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $factors = Factor::query()->with('store', 'category');
        } elseif ($user->hasRole('seller')) {

            $storeIds = $user->stores()->pluck('id');

            $factors = Factor::where(function ($query) use ($storeIds, $user) {

                $query->whereIn('store_id', $storeIds)
                    ->orWhere('user_id', $user->id);

        })->with('store', 'category');

            $factors = $factors->where('show_status', '!=',0);
        } else {
            return collect();
        }

        $searchQuery = $request->input('search_query');

        return $factors
            ->when($request->filled('search_query'), function ($q) use ($searchQuery) {
                $q->where(function ($query) use ($searchQuery) {
                    $query->where('id', 'LIKE', '%'.$searchQuery.'%');
                });
            })

            ->when($request->filled('show_status'), function ($q) use ($request) {
                $q->where('show_status', $request->show_status);
            })

            ->when($request->filled('price_status'), function ($q) use ($request) {
                $q->where('price_status', $request->price_status);
            })

            ->when($request->filled('store_id'), function ($q) use ($request) {
                if (auth()->user()->hasRole('admin')) {
                    $q->where('store_id', $request->store_id);
                }
            })

            ->when($request->filled('category_id'), function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            })

            ->when($request->filled('factor_date'), function ($q) use ($request) {
                $q->where('factor_date', Verta::parse($request->factor_date)->toCarbon());
            })

            ->when($request->filled('created_at'), function ($q) use ($request) {
                $q->whereDate('created_at', Verta::parse($request->created_at)->toCarbon());
            })

            ->when($request->filled('paid_factor_date'), function ($q) use ($request) {
                $q->where('paid_factor_date', Verta::parse($request->paid_factor_date)->toCarbon());
            })

            ->latest()
            ->paginate(10);
    }

    public function createFactor(array $data)
    {

        $data = $this->resolveBuyerFromStore($data);

        if (!empty($data['store_id'])) {
            $data['name'] = null;
            $data['phone'] = null;
            $data['national_kod'] = null;
            $data['user_id'] = null;
        }

        $factor = Factor::create([
            'store_id'     => $data['store_id'] ?? null,
            'user_id'      => $data['user_id'] ?? null,
            'factor_date'  => Verta::parse($data['factor_date'])->toCarbon(),
            'category_id'  => $data['category_id'],
            'show_status'  => $data['show_status'],
            'hash'         => Str::uuid(),
            'price'        => $data['price'],
            'name'         => $data['name'] ?? null,
            'phone'        => $data['phone'] ?? null,
            'national_kod' => $data['national_kod'] ?? null,
            'description'  => $data['description'] ?? null,
        ]);

        if (!empty($data['send_sms']) && $data['send_sms']) {
            $phone = $data['phone'] ?? null;
            $name  = $data['name'] ?? 'کاربر';

            if (empty($phone) && !empty($data['store_id'])) {
                $store = Stores::with('user')->find($data['store_id']);
                if ($store) {
                    $phone = $store->phone ?? null;
                    $name  = $store->user->name ?? 'فروشگاه';
                }
            }

            if ($phone) {
                (new SmsService())->sendFactorNotification($phone, $name, $factor->id, (int)$data['price']);
            }
        }

        return $factor;
    }

    public function updateFactor($id, $request)
    {
        $dateFactor = null;

        $factor = Factor::find($id);

        if ($request['price_status'] == 3)
        {
            $dateFactor =  Verta::parse($request['paid_factor_date'])->toCarbon();
        }

        $data = [
            'show_status' => $request['show_status'],
            'price_status' => $request['price_status'],
            'category_id' => $request['category_id'],
            'price' => $request['price'],
            'description' => $request['description'],
            'paid_factor_date' => $dateFactor,
            'factor_date' => Verta::parse($request['factor_date'])->toCarbon(),
        ];

        if (isset($request['image']) && $request['image']) {
            $data['image'] = $request['image']->store('factors', 'public');
        }

        $factor->update($data);
    }

    public function factorById($id)
    {
        return Factor::with(['store.user', 'category', 'customer'])->findOrFail($id);
    }

    private function resolveBuyerFromStore(array $data): array
    {
        if (empty($data['store_id'])) {
            return $data;
        }

        $store = Stores::with('user')->find($data['store_id']);
        if (!$store) {
            return $data;
        }

        $manager = $store->user;

        if (empty($data['name'])) {
            $data['name'] = $manager?->name ?? $store->store_name;
        }

        if (empty($data['phone'])) {
            $data['phone'] = $manager?->mobile ?? $store->phone ?? null;
        }

        if (empty($data['national_kod'])) {
            $data['national_kod'] = $manager?->national_kod ?? null;
        }

        if (empty($data['user_id']) && $store->user_id) {
            $data['user_id'] = $store->user_id;
        }

        return $data;
    }

    public function deleteFactor(int $id)
    {
        $factor=Factor::find($id);
        if($factor){
            return $factor->delete();
        }
        return false;
    }

    public function getHashById(int $id)
    {
        return Factor::where('id', $id)->value('hash');
    }
}
