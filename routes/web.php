<?php

use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\AuthorController;
use App\Models\Author;
use App\Models\Journal;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/data-skripsi', [JournalController::class, 'index'])->name('data-skripsi');

Route::get('/data-dosen', [AuthorController::class, 'index'])->name('data-dosen');

Route::get('/data-dosen/{id}', [AuthorController::class, 'show'])->name('data-dosen-detail');



Route::get('/data', function() {
    $model = Author::query()
    ->withCount('journals as total_publication');

    return DataTables::eloquent($model)
                ->addColumn('action', function($model) {
                    return '<a href="/data-dosen/'. $model->id .'" class="btn btn-outline-secondary">Detail</a>';
                })
                ->editColumn('code', function(Author $user) {
                    if (empty($user->code)) {
                        return '-';
                    } else {
                        return $user->code;
                    }
                })
                ->toJson();
})->name('data-dosen');