@extends('layouts.main-layout')
@section('main-content')
<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-inline d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>   
  <div class="page-heading d-flex flex-column">
    <h3>Data Skripsi</h3>
    <p class="text-xl font-bold">Data Skripsi Beserta Pengelompokan SDGs</p>
  </div>
  <div class="page-content">
    <section id="basic-vertical-layouts">
      <div class="row match-height">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Lakukan Pencarian</h4>
            </div>
            <div class="card-content">
              <div class="pt-0 px-4 pb-4">
                <form method="GET" action="/data-skripsi" class="form form-vertical">
                  <div class="form-body">
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <label class="mb-2" for="first-name-vertical">Judul Publikasi</label>
                          <input value="{{ Request::get('judul') }}" type="text" id="first-name-vertical" class="form-control" name="judul" placeholder="Ketik disini..." />
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label class="mb-2" for="basicSelect">SDGs</label>
                          <fieldset class="form-group">
                            <select class="form-select js-example-basic-multiple" name="sdgs[]" multiple="multiple">

                                @for($i = 1; $i <= $response['totalSdgs']; $i++)
                                  <option {{ Request::get('sdgs') == $i ? 'selected' : '' }} value="SDGS{{ $i }}">SDG{{ $i }}</option>
                                @endfor
                            </select>
                        </fieldset>
                        </div>
                      </div>
                      <div class="col-12 d-flex mt-2 justify-content-end">
                        <button type="submit" class="btn btn-primary me-1 mb-1">Cari</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="row">
      <div class="col-12">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Publikasi</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-lg fs-7">
                      <thead>
                          <tr>
                              <th >No</th>
                              <th>Judul Publikasi</th>
                              <th>Penulis</th>
                              <th>SDGs</th>
                          </tr>
                      </thead>
                      <tbody>
                        @if ($response['journals']->count() != 0)
                          @foreach ($response['journals'] as $publication)
                            <tr>
                              <td >{{ ($response['journals']->currentPage() - 1) * $response['journals']->perPage() + $loop->iteration }}</td>
                              <td class="col-7 text-bold-500">{{ $publication->title }}</td>
                              
                              @if (!empty($publication->authors))
                                <td class="col-2">
                                @foreach ($publication->authors as $author)

                                    @if($publication->authors->count())
                                      <a class="hover-opacity" href="/data-dosen/{{ $author->id }}" > {{ $author->name }}</a>
                                    @else
                                      NO AUTHOR
                                    @endif

                                @endforeach

                                </td>
                              @else
                                <td class="col-2">No Author</td>
                              @endif
                              <td class="col-3 text-bold-500">
                                <div class="d-flex gap-1 flex-wrap">
                                  @php
                                      $sdgs = explode(';', $publication->sdgs);
                                  @endphp

                                  @foreach ($publication->sdgs as $sdg)
                                  <a class="hover-opacity" href={{ "/data-skripsi?sdgs=SDGS" . $sdg->id }}>
                                    <img  class="rounded-2" style="width: 3rem" src="{{ asset('img/'. 'SDGS'.$sdg->id .'.png') }}" alt="SDGS{{$sdg->id}}">
                                  </a>
                                  @endforeach
                                </div>
                              </td>
                            </tr>
                          @endforeach
                            
                        @else
                          <tr>
                              <td class="text-center" colspan="5">Tidak ada data.</td>
                          </tr>
                        @endif
                      </tbody>
                  </table>
                </div>
                {{ $response['journals']->links() }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

</div>


@endsection