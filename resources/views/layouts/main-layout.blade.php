<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SDG Distribution Statistic - Telkom University</title>

    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />


    <link rel="stylesheet" href={{ asset("template/assets/compiled/css/app.css") }} />
    <link rel="stylesheet" href={{ asset("css/style.css") }} />


    <link href="https://cdn.datatables.net/v/bs5/dt-2.0.0/datatables.min.css" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    
  </head>

  <body>
    <div id="app">
      
      @include('partials.sidebar')

      @yield('main-content')
    </div>

    <script src={{ asset("template/assets/compiled/js/app.js")  }}></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.0.0/datatables.min.js"></script>

    <script>
      $(document).ready(function () {
          $('#dosen-table').DataTable({
              serverSide: true,
              processing: true,
              ajax: "{{ route('data-dosen') }}",
              columns: [
                  { data: 'id', name: 'id' },
                  { data: 'name', name: 'name' },
                  { data: 'code', name: 'code' },
                  { data: 'total_publication', name: 'total_publication',searchable: false, orderable : false },
                  { data: 'action', name: 'action' },
              ]
          });
      });
    </script>

  <script>
    $(document).ready(function() {
      $('.js-example-basic-multiple').select2({
        placeholder: "Pilih SDG",
        theme: "bootstrap-5",
      });
    });
  </script>

  <script>
    @if (Session::has('error'))
      alertify.error("{{ Session::get('error') }}");
    @endif
  </script>
  </body>
</html>
