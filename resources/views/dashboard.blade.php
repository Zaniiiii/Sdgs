@extends('layouts.main-layout')
@section('main-content')
<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-inline d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>   

  <div class="page-heading d-flex flex-column">
    <h3>Dashboard</h3>
    <p class="text-xl font-bold">Pemetaan 17 Bidang Tujuan Pembangunan Berkelanjutan (SDGs) Dosen Telkom University</p>
  </div>
  <div class="page-content">
    <section class="row">
      <div class="col-12">
        <div class="row">
        
          <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
              <div class="card-body px-4 py-4-5">
                <div class="row">
                  <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start pe-xxl-0">
                    <div style="background-color: #9694ff" class="dashboard-icon mb-2">
                      <img style="width: 1.5rem; filter: invert(1);" src="img/people-fill.svg" alt="">
                    </div>
                  </div>
                  <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8 ps-xxl-0">
                    <h6 class="text-muted font-semibold">Total Dosen</h6>
                    <h6 class="font-extrabold mb-0">{{ $response['totalAuthor']}}</h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
              <div class="card-body px-4 py-4-5">
                <div class="row">
                  <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start pe-xxl-0">
                    <div style="background-color: #57caeb" class="dashboard-icon mb-2">
                      <img style="width: 1.5rem; filter: invert(1);" src="img/file-text-fill.svg" alt="">
                    </div>
                  </div>
                  <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8 ps-xxl-0">
                    <h6 class="text-muted font-semibold">Total Skripsi</h6>
                    <h6 class="font-extrabold mb-0">{{ $response['totalPublication'] }}</h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
              <div class="card-body px-4 py-4-5">
                <div class="row">
                  <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start pe-xxl-0">
                    <div style="background-color: #5ddab4" class="dashboard-icon mb-2">
                      <img style="width: 1.5rem; filter: invert(1);" src="img/calendar-fill.svg" alt="">
                    </div>
                  </div>
                  <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8 ps-xxl-0">
                    <h6 class="text-muted font-semibold">Tahun</h6>
                    <h6 class="font-extrabold mb-0">{{ $response['publication_year'] }}</h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
              <div class="card-body px-4 py-4-5">
                <div class="row">
                  <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start pe-xxl-0">
                    <div style="background-color: #ff7976" class="dashboard-icon mb-2">
                      <img style="width: 1.5rem; filter: invert(1);" src="img/lightbulb-fill.svg" alt="">
                    </div>
                  </div>
                  <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8 ps-xxl-0">
                    <h6 class="text-muted font-semibold">SDGs</h6>
                    <h6 class="font-extrabold mb-0">17</h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>SDGs</h4>
              </div>
              <div class="card-body">
                <div class="sdg-container">
                  @for ($i = 1; $i <= 17; $i++)
                      <a href={{ "/data-skripsi?sdgs=SDGS" . $i }} class="sdg-item">
                          <img class="hover-opacity w-100 rounded-3" src="img/SDGS{{ $i }}.png" alt="sdg-{{ $i }}">
                      </a>
                  @endfor

                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card w-100">
              <div class="card-header">
                <h4>SDGs Distribution</h4>
              </div>
              <div class="card-body row">
                <div class="col-12 d-flex flex-column flex-lg-row align-items-md-center align-items-lg-stretch  justify-content-center">
                    <div class="d-flex justify-content-center">
                      <div id="chart-sdg"></div>
                    </div>
                    <div class="chart-sdg-legend h-full">
                      @foreach($response['sdgCountArray'] as $key => $value)
                      <div class="chart-sdg-legend-item">
                        <div class="chart-sdg-legend-item-marker {{ $key }}"></div>
                        <div class="chart-sdg-legend-item-label">{{ $key }}</div>
                      </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <p></p>
    </section>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
  let colors = ['#EB1C2E', '#D3A02A', '#279B48', '#C21F32', '#EF3E2A','#05AED9','#FCB712','#8F1838','#F36D26','#E11E87','#FA9D26','#CF8E29','#48783E','#037DBC','#3DB049','#04558C','#183668']
  let color = '#279B48'
   var options = {
          series:  @json(
            array_values($response['sdgCountArray'])
          ),
          chart: {
          width: 600,
          type: 'pie',
        },
        dataLabels: {
          enabled: true,
          formatter: function (val,{ seriesIndex, dataPointIndex, w }) {
            // return val
            return w.config.labels[seriesIndex].split(" - ")[0] + " :  " + Math.round(val) + "%";
          }
        },
        labels: 
        [
          @foreach($response['sdgCountArray'] as $key => $value)
            "{{ $key }}",
          @endforeach
        ],
        legend: {
          show: false,
          showForSingleSeries: false,
          showForNullSeries: true,
          showForZeroSeries: true,
          position: 'right',
          horizontalAlign: 'center', 
          floating: false,
          fontSize: '15px',
          fontFamily: 'Nunito, sans-serif',
          fontWeight: 400,
          formatter: undefined,
          inverseOrder: false,
          width: undefined,
          height: undefined,
          tooltipHoverFormatter: undefined,
          customLegendItems: [],
          offsetX: 0,
          offsetY: 0,
          labels: {
              colors: undefined,
              useSeriesColors: false
          },
          markers: {
              width: 12,
              height: 12,
              strokeWidth: 0,
              strokeColor: '#fff',
              fillColors: undefined,
              radius: 12,
              customHTML: undefined,
              onClick: undefined,
              offsetX: 0,
              offsetY: 0
          },
          itemMargin: {
              horizontal: 0,
              vertical: 10
          },
          onItemClick: {
              toggleDataSeries: true
          },
          onItemHover: {
              highlightDataSeries: true
          },
      },
        colors: ['#EB1C2E', '#D3A02A', '#279B48', '#C21F32', '#EF3E2A','#05AED9','#FCB712','#8F1838','#F36D26','#E11E87','#FA9D26','#CF8E29','#48783E','#037DBC','#3DB049','#04558C','#183668'],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 340,
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
              width: 400
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
              width: 500
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
              width: 650
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
              width: 600
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
              width: 600
            },
            legend: {
              position: 'bottom'
            }
          }
        }
      ]
        };

        var chart = new ApexCharts(document.querySelector("#chart-sdg"), options);
        chart.render();
</script>
@endsection