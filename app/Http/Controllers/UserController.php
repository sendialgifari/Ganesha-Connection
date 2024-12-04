<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Spatie\Permission\Models\Role;
use App\Models\Product;
use App\Models\Service;
use App\Models\Community;
use App\Models\ProductImage;
use App\Models\ServiceImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Auth;
use Carbon\Carbon;
use Hash;
use ProtoneMedia\Splade\Facades\Toast;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function partner_registration()
    {
        if (Auth::user()->getRoleNames()[0] != "user") {
            return Redirect::to('dashboard');
        }
        return view('partner_registration', [
            'gender' => [
                'l' => 'Laki-laki',
                'p' => 'Perempuan',
            ],
            'provinces' => Province::pluck('prov', 'id')->toArray(),
            'communities' => Community::pluck('name', 'id')->toArray(),
        ]);
    }

    public function partner_approval()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('name', 'LIKE', "%{$value}%")
                        ->orWhere('email', 'LIKE', "%{$value}%");
                });
            });
        });

        $users = QueryBuilder::for(User::where('is_verified', 0)->role('partner'))
            ->defaultSort('name')
            ->allowedSorts(['name', 'email'])
            ->allowedFilters(['name', 'email', $globalSearch])
            ->paginate()
            ->withQueryString();

        return view('partner_approval', [
            'users' => SpladeTable::for($users)
                ->defaultSort('name')
                ->withGlobalSearch()
                ->column('name', 'Nama', sortable: true, searchable: true)
                ->column('email', sortable: true, searchable: true)
                ->column('unique_id', 'Ijazah/NIM/NIP')
                ->column('community.name', 'Civitas')
                ->column('phone_number', 'Telepon')
                ->column('province.prov', 'Provinsi')
                ->column('city.kabupaten_kota', 'Kabupaten Kota')
                ->column('address', 'Alamat')
                ->column('gender', 'Jenis Kelamin')
                ->column('action', 'Aksi')
            ,
        ]);
    }


    public function partner_approval_update(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->update([
            'is_verified' => 1,
            // 'partner_approval_date' => Carbon::now(),
        ]);
        // $user->syncRoles(6);

        Toast::title('User berhasil diverifikasi!')->autoDismiss(5);
        return redirect()->route('partner_approval');
    }

    public function partner_decline_update(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->update([
            'partner_approval' => 0,
            'partner_approval_date' => Carbon::now(),
        ]);
        $user->syncRoles(5);

        Toast::title('User gagal terverifikasi!')->autoDismiss(5);
        return redirect()->route('partner_approval');
    }

    public function user_update_public(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->update([
            'is_public' => $request->is_public,
        ]);

        Toast::title('Status Anda berhasil diperbarui')->autoDismiss(5);
        return redirect()->back();
    }

    public function partner_registration_update(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'gender' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'address' => 'required',
            'phone_number' => 'required|numeric|min:2',
            'description' => 'required',
            'unique_id' => 'required',
            'community_id' => 'required',
        ])->validate();

        $slug = str_replace(array(" ", ".", ",", "'", '"', "?", "!", ":", "/"), array("-", "-", "", "", "", "", "", "", ""), strtolower($request->name)) . "-" . Auth::user()->id;

        $user = User::where('id', Auth::user()->id)->first();
        $user->update([
            'name' => $request->name,
            'slug' => $slug,
            'email' => $request->email,
            'gender' => $request->gender,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'description' => $request->description,
            'unique_id' => $request->unique_id,
            'community_id' => $request->community_id,
            'partner_approval' => 0,
            'partner_approval_date' => Carbon::now(),
        ]);
        $user->syncRoles(6);

        Toast::title('Daftar Partner Berhasil!')->autoDismiss(5);
        return redirect()->route('partner_registration');
    }
    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('name', 'LIKE', "%{$value}%")
                        ->orWhere('email', 'LIKE', "%{$value}%");
                });
            });
        });

        // $users = QueryBuilder::for(User::whereDoesntHave('roles', function($q) {
        //     $q->where('name', 'admin');
        // }))
        $users = QueryBuilder::for(User::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'email', 'is_selected'])
            ->allowedFilters(['name', 'email', AllowedFilter::exact('roles.id'), $globalSearch])
            ->paginate()
            ->withQueryString();

        // $roles = Role::where('name', '!=', 'admin')->pluck('name', 'id')->toArray();
        $roles = Role::pluck('name', 'id')->toArray();

        return view('users.index', [
            'users' => SpladeTable::for($users)
                ->defaultSort('name')
                ->withGlobalSearch()
                ->column('name', 'Nama', sortable: true, searchable: true)
                ->column('email', sortable: true, searchable: true)
                ->column('roles.name', 'Role', sortable: true, searchable: true)
                ->column('is_selected', 'Pilihan', sortable: true, searchable: true)
                ->column('unique_id', 'Ijazah/NIM/NIP')
                ->column('community.name', 'Civitas')
                ->column('phone_number', 'Telepon')
                ->column('province.prov', 'Provinsi')
                ->column('city.kabupaten_kota', 'Kabupaten Kota')
                ->column('address', 'Alamat')
                ->column('gender', 'Jenis Kelamin')
                ->selectFilter('roles.id', $roles)
                ->column('action')
            ,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'id')->toArray();
        $is_selected = [
            '0' => 'No',
            '1' => 'Yes',
        ];
        return view('users.create', compact('roles', 'is_selected'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255|unique:users,name',
            'email' => 'required|max:255|unique:users,email',
            'password' => 'required|min:8',
            'roles' => 'required',
            'is_selected' => 'required',
        ])->validate();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_selected' => $request->is_selected,
        ]);
        $user->syncRoles((int) $request->roles);

        Toast::title('User berhasil dibuat!')->autoDismiss(5);

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'id')->toArray();
        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'is_selected' => [
                '0' => 'No',
                '1' => 'Yes',
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'roles' => 'required',
            'is_selected' => 'required',
        ])->validate();

        $roles = $request->roles;

        if(is_array($request->roles)){
            $roles = $request->roles[0];
        }

        if ($request->password) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_selected' => $request->is_selected,
            ]);
        } else {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'is_selected' => $request->is_selected,
            ]);
        }
        $user->syncRoles((int) $roles);

        Toast::title('User berhasil diperbarui!')->autoDismiss(5);

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $products = Product::where('user_id', $user->id)->get();
        foreach ($products as $product) {
            $product->product_comments()->delete();
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/products/' . str_replace('/storage/products/', '', $product->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/products/' . str_replace('/storage/products/', '', $product->image_thumb);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $product_images = ProductImage::where('product_id', $product->id)->get();
            foreach ($product_images as $key => $value) {
                $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/products/' . str_replace('/storage/products/', '', $value->image);
                try {
                    unlink($dirname);
                } catch (\Throwable $th) {
                }
            }

            ProductImage::where('product_id', $product->id)->delete();

            $product->work_units()->detach();
            $product->delete();
        }

        $services = Service::where('user_id', $user->id)->get();
        foreach ($services as $service) {
            $service->service_comments()->delete();
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/services/' . str_replace('/storage/services/', '', $service->image);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }
            $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/services/' . str_replace('/storage/services/', '', $service->image_thumb);
            try {
                unlink($dirname);
            } catch (\Throwable $th) {
            }

            $service_images = ServiceImage::where('service_id', $service->id)->get();
            foreach ($service_images as $key => $value) {
                $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/services/' . str_replace('/storage/services/', '', $value->image);
                try {
                    unlink($dirname);
                } catch (\Throwable $th) {
                }
            }

            ServiceImage::where('service_id', $service->id)->delete();

            $service->work_units()->detach();
            $service->delete();
        }

        $dirname = '/usr/share/nginx/html/ganeshaconnection/storage/app/public/profile-photos/' . str_replace('/storage/profile-photos/', '', $user->profile_photo_path);
        try {
            unlink($dirname);
        } catch (\Throwable $th) {
        }
        $user->delete();
        Toast::title('User berhasil dihapus!')->danger()->autoDismiss(5);

        return redirect()->route('users.index');
    }
}
