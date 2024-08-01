@extends('layouts.main-layout')
@section('main-content')
<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-inline d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>   


  <div class="page-heading d-flex flex-column">
    <h3>Data Dosen</h3>
    <p class="text-xl font-bold">Data Dosen beserta Pengelompokan SDGs</p>
  </div>
  <div class="page-content">
    <section id="basic-vertical-layouts">
      <div class="row match-height">
        <div class="col-12">
          <div class="card">
            <div class="card-header mb-3">
              <div class="d-flex justify-content-between align-items-center">

                <h4 class="card-title">Detail Dosen</h4>
                <a href="/data-dosen" class="btn btn-primary">Kembali</a>
              </div> 
            </div>
            <div class="card-body">
              <div class="row mb-5 d-flex flex-column flex-md-row">
                @php
                $authorDetails = [
                    'Nama' => $response["author"]->front_title . " " . $response["author"]->name . " " . $response["author"]->back_title,
                    'NIDN' => $response["author"]->nidn ?? '-',
                    'Kode Dosen' => $response["author"]->code ?? '-',
                    'Jenis Kelamin' => $response["author"]->gender ?? '-',
                    'Lokasi Kerja' => $response["author"]->work_location ?? '-',
                    'Jabatan Struktural' => $response["author"]->position ?? '-',
                    'Status Pegawai' => $response["author"]->employment_status ?? '-',
                ];
            @endphp
            
            <div class="col-12 col-md-6 pt-2">
                <table class="table table-bordered">
                    <tbody>
                        @foreach ($authorDetails as $label => $value)
                            <tr>
                                <td class="fw-bolder">{{ $label }}</td>
                                <td>:</td>
                                <td>{{ $value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
                <div class="col-md-6 d-flex mt-4 mt-md-0 justify-content-center justify-content-md-end">
                  <div class="rounded-4 overflow-hidden d-flex justify-content-end items-stretch">
                    <div class="bg-primary-light pt-2 border border-2 rounded-4 shadow-md h-100 d-flex flex-column justify-center-stretch">
                      <p class="text-center fw-semibold">Diagram Distribusi SDG</p>
                      <div class="flex-1 mt-md-5 mt-lg-0">

                        <div id="chart-sdg-dosen"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="table-responsive mt-4">
                <table class="table table-lg">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Publikasi</th>
                            <th>Penulis</th>
                            <th>SDGs</th>
                        </tr>
                    </thead>
                    <tbody>
                      @if ($response['journals']->count() != 0)
                        @foreach ($response['journals'] as $publication)
                          <tr>
                            <td class="col-1">{{ ($response['journals']->currentPage() - 1) * $response['journals']->perPage() + $loop->iteration }}</td>
                            <td class="col-7 text-bold-500">{{ $publication->title }}</td>
                            @if (!empty($publication->authors))
                            <td class="col-2">
                              @foreach ($publication->authors as $author)

                                  @if($publication->authors->count())
                                    <a href="/data-dosen/{{ $author->id }}" > {{ $author->name }}</a>,
                                  @else
                                    NO AUTHOR
                                  @endif

                              @endforeach

                              </td>
                            @else
                              <td class="col-2">No Author</td>
                            @endif

                            @php
                                $sdgs = explode(';', $publication->sdgs);
                            @endphp

                            <td class="col-2 text-bold-500">
                              <div class="d-flex gap-1 flex-wrap">

                                @foreach ($publication->sdgs as $sdg)
                                <img class="rounded-2" style="width: 3rem" src="{{ asset('img/'. 'SDGS'.$sdg->id .'.png') }}" alt="SDGS{{$sdg->id}}">
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

              {{-- <section class="row">
                <div class="col-12">
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-header">
                          <h4>Publikasi</h4>
                        </div>
                        <div class="card-body">
                         
                        </div>
                      </div>
                    </div>
                  </div>
             
                </div>
               
              </section> --}}


              {{-- <div class="table-responsive mt-5">
                <table class="table table-lg">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Publikasi</th>
                            <th>SDGs</th>
                        </tr>
                    </thead>
                    <tbody>

                      @foreach ($journals as $journal)
                        <tr>
                          <td>{{ ($journals->currentPage() - 1) * $journals->perPage() + $loop->iteration }}</td>
                          <td class="text-bold-500">{{ $journal->Judul }}</td>
                          @php
                              $sdgs = explode(';', $journal->aspects);
                          @endphp

                          <td class="text-bold-500">
                            @foreach ($sdgs as $sdg)
                              <img class="rounded-2" style="width: 3rem" src="{{ asset('img/'.$sdg.'.png') }}" alt="">
                            @endforeach
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
              </div> --}}
              {{-- {{ $publications->links() }} --}}
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
  var sdgCountArray = @json(array_values($response['sdgCountArray']));

  var allZeros = sdgCountArray.every(function(value) {
      return value === 0;
  });

   var options = {
    series: allZeros ? [] : sdgCountArray,
        chart: {
          width: 320,
          type: 'pie',
        },
        legend: {
          show: false,
      },
        dataLabels: {
          enabled: true,
          formatter: function (val,{ seriesIndex, dataPointIndex, w }) {
            return w.config.labels[seriesIndex].split(" - ")[0] + ":  " + Math.round(val) + "%";
          }
        },
        labels: 
        [
          @foreach($response['sdgCountArray'] as $key => $value)
            "{{ $key }}",
          @endforeach
        ],
        colors: ['#EB1C2E', '#D3A02A', '#279B48', '#C21F32', '#EF3E2A','#05AED9','#FCB712','#8F1838','#F36D26','#E11E87','#FA9D26','#CF8E29','#48783E','#037DBC','#3DB049','#04558C','#183668'],
        noData: {
          text: 'Tidak ada data',
          align: 'center',
          verticalAlign: 'top',
          offsetX: 0,
          offsetY: 50,
          style: {
              color: '#999',
              fontSize: '14px',
              fontFamily: 'Nunito, Helvetica, Arial, sans-serif'
          }
        },
        responsive: [
          {
          breakpoint: 326,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        },  
        {
          breakpoint: 480,
          options: {
            chart: {
              width: 260
            },
            legend: {
              position: 'bottom'
            }
          }
        },
        {
          breakpoint: 576,
          options: {
            chart: {
              width: 300
            },
            legend: {
              position: 'bottom'
            }
          }
        },
        {
          breakpoint: 768,
          options: {
            chart: {
              width: 300
            },
            legend: {
              position: 'bottom'
            }
          }
        },
        {
          breakpoint: 992,
          options: {
            chart: {
              width: 300
            },
            legend: {
              position: 'bottom'
            }
          }
        },
        {
          breakpoint: 1200,
          options: {
            chart: {
              width: 330
            },
            legend: {
              position: 'bottom'
            }
          }
        },
        {
          breakpoint: 1400,
          options: {
            chart: {
              width: 330
            },
            legend: {
              position: 'bottom'
            }
          }
        }
      ]
        };

        var chart = new ApexCharts(document.querySelector("#chart-sdg-dosen"), options);
        chart.render();
</script>
    
@endsection