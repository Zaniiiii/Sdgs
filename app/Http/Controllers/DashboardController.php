<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sdg;
use App\Models\Journal;
use App\Models\Author;

class DashboardController extends Controller
{
    public function index(){
        $sdgCountArray = [];
        $sdgs = Sdg::all();

        foreach ($sdgs as $sdg) {
            $sdgCountArray[$sdg->code] = $sdg->journals->count();
        }

        $response = [
            'totalPublication' => Journal::count(), // Total Journal
            'totalAuthor' => Author::count(), // Total Author
            'totalSdg' => Sdg::count(), // Total SDG
            'sdgCountArray' => $sdgCountArray, // array jumlah sdg setiap journal

            'totalAuthor' => Author::count(), // Total Author
            'publication_year' => ' 2018 - 2024'
        ];
        return view('dashboard',  ['response' => $response]);
    }
}