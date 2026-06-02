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
        return Category::paginate(15);
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
    
        \Log::info('SMS Data:', [
            'send_sms' => $data['send_sms'] ?? 'not set',
            'phone'    => $data['phone'] ?? null,
            'name'     => $data['name'] ?? null,
            'price'    => $data['price'],
        ]);
    
        if (!empty($data['send_sms']) && $data['send_sms']) {
            $phone = $data['phone'] ?? null;
            $name  = $data['name'] ?? 'کاربر';
    
            if (empty($phone) && !empty($data['store_id'])) {
                $store = Stores::find($data['store_id']);
                if ($store) {
                    $phone = $store->phone ?? $store->mobile ?? null;
                    $name  = $store->store_name ?? 'فروشگاه';
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

        $factor = Factor::find($id);

        $data = [
            'show_status' => $request['show_status'],
            'price_status' => $request['price_status'],
            'category_id' => $request['category_id'],
            'price' => $request['price'],
            'description' => $request['description'],
            'paid_factor_date' => ! empty($request['paid_factor_date']) ? Verta::parse($request['paid_factor_date'])->toCarbon() : null,            'factor_date' => Verta::parse($request['factor_date'])->toCarbon(),
        ];

        if (isset($request['image']) && $request['image']) {
            $data['image'] = $request['image']->store('factors', 'public');
        }

        $factor->update($data);
    }

    public function factorById($id)
    {
        return Factor::with('store', 'category')->findOrFail($id);
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
