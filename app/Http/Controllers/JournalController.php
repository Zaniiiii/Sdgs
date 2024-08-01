<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Journal;
use App\Models\Author;
use App\Models\Sdg;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->query('judul');
        $sdgs = $request->query('sdgs');

        $journals = Journal::query()->with('authors', 'sdgs');

        if ($title) {
            $journals = $journals->where('title', 'like', '%' . $title . '%');
        }


        if ($sdgs) {
            // Check if $sdgs is a string and convert it to an array if necessary
            if (is_string($sdgs)) {
                $journals = $journals->whereHas('sdgs', function ($query) use ($sdgs) {
                    $query->where('code', $sdgs);
                }); // Convert the comma-separated string to an array
            } else {
                foreach ($sdgs as $sdg) {
                    $journals = $journals->whereHas('sdgs', function ($query) use ($sdg) {
                        $query->where('code', $sdg);
                    });
                }
            }

        }

        
        $journals = $journals->paginate(10)->withQueryString();

        $response = [
            'journals' => $journals,
            'totalSdgs' => Sdg::count()
        ];

        return view('data-skripsi', ['response' => $response]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePublicationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publication $publication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePublicationRequest $request, Publication $publication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication)
    {
        //
    }
}
