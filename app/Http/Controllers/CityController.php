<?php

namespace App\Http\Controllers;

use App\Enums\RedirectType;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use RedirectHelperTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_unless(checkAdminHasPermission('city.list'), 403);
        $cities = City::with(['state' => function ($q) {
            return $q->with('country');
        }])->when(request()->filled('keyword'), function ($q) {
            $q->where('name', 'like', '%' . request('keyword') . '%')
                ->orWhereHas('state', function ($query) {
                    $query->where('name', 'like', '%' . request('keyword') . '%');
                })->orWhereHas('state.country', function ($query) {
                $query->where('name', 'like', '%' . request('keyword') . '%');
            });
        })->when(request()->filled('order_by'), function ($q) {
            $q->orderBy('name', request('order_by'));
        }, function ($q) {
            $q->latest();
        })->paginate(50)->withQueryString();

        return view('admin.locations.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(checkAdminHasPermission('city.create'), 403);
        $states    = State::all();
        $countries = Country::all();

        return view('admin.locations.cities.create', compact('states', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(checkAdminHasPermission('city.store'), 403);

        $request->validate([
            'name'     => 'required',
            'state_id' => 'required',
        ], [
            'name.required'     => __('Name is Required'),
            'state_id.required' => __('State is Required'),
        ]);

        $city           = new City;
        $city->name     = trim($request->name);
        $city->state_id = $request->state_id;
        $city->save();

        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.city.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_unless(checkAdminHasPermission('city.edit'), 403);

        $city = City::find($id);

        $countries   = Country::all();
        $cityCountry = $city->state->country ?? '';

        $states = State::where('country_id', $cityCountry->id)->get();

        if (!$city) {
            $notification = __('City Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('admin.city.index')->with($notification);
        }

        return view('admin.locations.cities.edit', compact('city', 'states', 'countries', 'cityCountry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_unless(checkAdminHasPermission('city.update'), 403);

        $city = City::find($id);

        if (!$city) {
            $notification = __('City Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('admin.city.index')->with($notification);
        }

        $request->validate([
            'name'     => 'required',
            'state_id' => 'required',
        ], [
            'name.required'     => __('Name is Required'),
            'state_id.required' => __('State is Required'),
        ]);

        $city->name     = trim($request->name);
        $city->state_id = $request->state_id;
        $city->save();

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.city.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_unless(checkAdminHasPermission('city.delete'), 403);

        $city = City::find($id);
        if (!$city) {
            $notification = __('City Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return $this->redirectWithMessage(RedirectType::ERROR->value, 'admin.city.index');
        } else {
            $city->delete();
            $notification = __('Delete Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.city.index');
        }
    }

    /**
     * Get all resources By State Id from storage.
     */
    public function getAllCitiesByState(string $id)
    {
        if (str_contains($id, ',')) {
            $id     = explode(',', $id);
            $cities = City::whereIn('state_id', $id)->get();
            if ($cities->count() > 0) {
                return ['status' => 200, 'data' => $cities];
            } else {
                return ['status' => 404, 'message' => __('Cities Not Found'), 'data' => []];
            }
        }

        $cities = State::find($id)->cities;
        if ($cities->count() > 0) {
            return ['status' => 200, 'data' => $cities];
        } else {
            return ['status' => 404, 'message' => __('Cities Not Found'), 'data' => []];
        }
    }
}
