<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>
    Argon Dashboard 3 by Creative Tim
  </title>

  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

  <!-- jQuery (Load first to avoid conflicts) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('style/assets/css/argon-dashboard.css?v=2.1.0') }}" rel="stylesheet" />

  <!-- CSS DataTables - Using Bootstrap 5 styling -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  
  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  
  <!-- Additional CSS -->
  @stack('styles')
</head>

<body>
  @include('component.sidebar')
  @include('component.navbar')

  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      @yield('content')
    </div>
  </main>

  <!-- Core JS Files -->
  <script src="{{ asset('style/assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/plugins/chartjs.min.js') }}"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  
  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = { damping: '0.5' };
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    
    // Setup AJAX CSRF token
    $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    
    // Configure Toastr
    toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "timeOut": "3000"
    };
  </script>

  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="{{ asset('style/assets/js/argon-dashboard.min.js?v=2.1.0') }}"></script>
  
  <!-- Additional Scripts -->
  @stack('scripts')
</body>

</html>

