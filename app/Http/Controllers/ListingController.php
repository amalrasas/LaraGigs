<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    //show all listings
    public function index()
    {

        return view('listings.index', [
            'listings' => listing::latest()->filter(request(['tag', 'search']))->simplePaginate(4)
        ]);
    }
    // Create Listing Data
    public function create()
    {
        return view('listings.create');
    }



    //show single listing
    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }
    //Store Listing Data
    public function store(Request $request)
    {
        $formFields = $request->validate(
            [
                'title' => 'required',
                'company' => 'required|unique:listing,company',
                'location' => 'required',
                'website' => 'required',
                'email' => ['required', 'email'],
                'tags' => 'required',
                'description' => 'required'
            ]
        );
        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $formFields['user_id'] = auth()->id();
        Listing::create($formFields);
        return redirect('/')->with('message', 'Listing created successfully!');
    }
    // Show Edit Form
    public function edit(Listing $listing)
    {
        return view('listing.edit', ['listing' => $listing]);
    }
    public function update(Request $request, Listing $listing)
    {
        // make sure logged in user is owner
        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action!');
        }

        $formFields = $request->validate(
            [
                'title' => 'required',
                'company' => 'required',
                'location' => 'required',
                'website' => 'required',
                'email' => ['required', 'email'],
                'tags' => 'required',
                'description' => 'required'
            ]
        );
        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $listing->update($formFields);
        return back()->with('message', 'Listing updated successfully!');
    }
    // Delete Listing
    public function destroy(Listing $listing)
    {
        // make sure logged in user is owner
        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action!');
        }
        $listing->delete();
        return redirect('/')->with('message', 'Listing deleted successfully!');
    }
    // Manage Listings
    public function manage()
    {
        return view('listings.manage', [
            // 'listings' => auth()->user()->listings()->get()
        ]);
    }
}
