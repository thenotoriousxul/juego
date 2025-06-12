<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\City;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;


class LocationController extends Controller
{

     public function showForm()
{
    $countries = Country::select('id', 'name')->get();

    return Inertia::render('LocationSelect', [
        'initialCountries' => $countries,
    ]);
}


    public function countries()
    {
        return Country::select('id', 'name')->get();
    }

    public function storeCity(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'country_id' => 'required|exists:countries,id',
        'name' => 'required|string|max:255',
    ]);

    City::create($validated);

    return back()->with('successMessage', 'Ciudad creada correctamente');
}
    public function citiesByCountry($countryId)
    {
        return City::where('country_id', $countryId)
                    ->select('id', 'name')
                    ->get();
    }
}

