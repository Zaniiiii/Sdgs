@extends('layouts.main-layout')
@section('main-content')
<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-inline d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>   

  {{-- @dd($publications) --}}

  <div class="page-heading d-flex flex-column">
    <h3>Data Dosen</h3>
    <p class="text-xl font-bold">Data Dosen beserta Pengelompokan SDGs</p>
  </div>
  <div class="page-content">
    <section class="row">
      <div class="col-12">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Daftar Dosen</h4>
              </div>
              <div class="card-body overflow-auto pt-2 flex-1">
                <table id="dosen-table" class="table table-stripped mt-4 mb-4">
                  <thead>
                      <tr>
                          <th>No</th>
                          <th>Nama Lengkap</th>
                          <th>Kode</th>
                          <th>Jumlah Publikasi</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
@endsection
