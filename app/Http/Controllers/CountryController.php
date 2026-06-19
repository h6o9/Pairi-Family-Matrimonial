<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_unless(checkAdminHasPermission('country.list'), 403);

        $query = Country::query();

        $query->when(request()->filled('keyword'), function ($q) {
            $q->where('name', 'like', '%' . request('keyword') . '%')
                ->orWhereRelation('states', 'name', 'like', '%' . request('keyword') . '%')
                ->orWhereHas('states.cities', function ($query) {
                    $query->where('name', 'like', '%' . request('keyword') . '%');
                });
        });

        $query->when(request()->filled('order_by'), function ($q) {
            $orderBy = request('order_by');
            $q->orderBy('name', $orderBy);
        }, function ($q) {
            $q->latest();
        });

        $countries = $query->get();

        return view('admin.locations.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(checkAdminHasPermission('country.create'), 403);

        return view('admin.locations.countries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(checkAdminHasPermission('country.store'), 403);
        $request->validate([
            'name' => 'required|unique:countries,name',
        ], [
            'name.required' => 'Name is Required',
            'name.unique'   => 'Name is Already Exists',
        ]);

        $country       = new Country;
        $country->name = trim($request->name);
        $country->save();

        $notification = trans('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.country.index')->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_unless(checkAdminHasPermission('country.edit'), 403);

        $country = Country::find($id);
        if (!$country) {
            $notification = __('Country Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('admin.country.index')->with($notification);
        }

        return view('admin.locations.countries.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_unless(checkAdminHasPermission('country.update'), 403);

        $country = Country::find($id);
        if (!$country) {
            $notification = __('Country Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('admin.country.index')->with($notification);
        }
        $request->validate([
            'name' => 'required|unique:countries,name,' . $id,
        ], [
            'name.required' => 'Name is Required',
            'name.unique'   => 'Name is Already Exists',
        ]);

        $country->name = trim($request->name);
        $country->save();

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.country.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_unless(checkAdminHasPermission('country.delete'), 403);

        $country = Country::find($id);
        if (!$country) {
            $notification = __('Country Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('admin.country.index')->with($notification);
        } else {
            $country->delete();
            $notification = __('Delete Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->route('admin.country.index')->with($notification);
        }
    }
}
