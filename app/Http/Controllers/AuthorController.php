<?php

namespace App\Http\Controllers;

use App\DataTables\AuthorsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Journal;
use App\Models\Sdg;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorsDataTable $dataTable)
    {
        // $authors = Author::all();
        // dd($authors);

        // return view('data-dosen', ['authors' => $authors]);

        return $dataTable->render('data-dosen');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $data = json_decode($request->input('data'), true);
            $counter = 0;
            foreach ($data as $item) {
                $author = Author::create([
                    'nidn' => $item['NIDN'] ?? '-',
                        'name' => $item['NAMA LENGKAP'] ?? '-',
                        'gender' => ($item['JENIS KELAMIN'] ?? '-') == 'WANITA' ? 'P' : 'L',
                        'position' => $item['JABATAN STRUKTURAL'] ?? '-',
                        'employment_status' => $item['STATUS PEGAWAI'] ?? '-',
                        'front_title' => $item['FRONTTITLE'] ?? '-',
                        'back_title' => $item['BACKTITLE'] ?? '-',
                        'code' => $item['KODE DOSEN'] ?? '-',
                        'work_location' => $item['LOKASI KERJA'] ?? '-', ]
                );
                if ($author->wasRecentlyCreated) {
                    $counter++;
                }
            }
            print_r('Total data saved: ' . $counter);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
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
    public function show($id)
    {
        $sdgCountArray = [];
        $sdgs = Sdg::all();

        $countArr =  Journal::whereHas('authors', function ($query) use ($id) {
            $query->where('authors.id', $id);
        })->whereHas('sdgs', function ($query) use ($id) {
            $query->where('sdgs.code', "SDGS1");
        })->count();
        
        foreach ($sdgs as $sdg) {
            $sdgCountArray[$sdg->code] = Journal::whereHas('authors', function ($query) use ($id) {
                $query->where('authors.id', $id);
            })->whereHas('sdgs', function ($query) use ($sdg) {
                $query->where('sdgs.code', $sdg->code);
            })->count();
        }
        try {
            $author = Author::find($id);
        } catch (\Exception $e) {
            Log::error('An error occurred while finding the author: ' . $e->getMessage());
        }

        $journals = Journal::whereHas('authors', function ($query) use ($id) {
            $query->where('authors.id', $id);
        })->paginate(10);

        $response = [
            'author' => $author,
            'journals' => $journals,
            'sdgCountArray' => $sdgCountArray
        ];
        
        
        return view('data-dosen-detail', ['response' => $response]);
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
