<!-- Required vendors -->
<script src="{{asset('asset/vendor/global/global.min.js')}}"></script>
<script src="{{asset('asset/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
<script src="{{asset('asset/vendor/chart.js/Chart.bundle.min.js')}}"></script>
<script src="{{asset('asset/js/custom.min.js')}}"></script>
<script src="{{asset('asset/js/deznav-init.js')}}"></script>

<!-- Apex Chart -->
<script src="{{asset('asset/vendor/apexchart/apexchart.js')}}"></script>
<!-- Vectormap -->
<!-- Chart piety plugin files -->
<script src="{{ asset('asset/vendor/peity/jquery.peity.min.js') }}"></script>

<!-- Chartist -->
<script src="{{ asset('asset/vendor/chartist/js/chartist.min.js') }}"></script>

 <!-- Datatable -->
<script src="{{asset('asset/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('asset/js/plugins-init/datatables.init.js')}}"></script>

<!-- Dashboard 1 -->
<script src="{{ asset('asset/js/dashboard/dashboard-1.js') }}"></script>
<!-- <script src="{{ asset('asset/vendor/svganimation/vivus.min.js') }}"></script> -->
<!-- Svganimation scripts -->
<script src="{{ asset('asset/vendor/svganimation/vivus.min.js')}}"></script>
<script src="{{ asset('asset/vendor/svganimation/svg.animation.js') }}"></script>
<!-- select2 -->
<script src="{{ asset('asset/vendor/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('asset/js/plugins-init/select2-init.js') }}"></script>
<!-- Datatable -->
<script src="{{ asset('asset/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('asset/js/plugins-init/datatables.init.js') }}"></script>
<!-- call assets -->
<script src="{{ asset('/js/sweetalert2.all.min.js') }}"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script> -->

<!-- Daterangepicker -->
<!-- momment js is must -->
<script src="{{ asset('asset/vendor/moment/moment.min.js')}}"></script>
<script src="{{ asset('asset/vendor/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- clockpicker -->
<script src="{{ asset('asset/vendor/clockpicker/js/bootstrap-clockpicker.min.js')}}"></script>
<!-- asColorPicker -->
<script src="{{ asset('asset/vendor/jquery-asColor/jquery-asColor.min.js')}}"></script>
<script src="{{ asset('asset/vendor/jquery-asGradient/jquery-asGradient.min.js')}}"></script>
<script src="{{ asset('asset/vendor/jquery-asColorPicker/js/jquery-asColorPicker.min.js')}}"></script>
<!-- Material color picker -->
<script src="{{ asset('asset/vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
<!-- pickdate -->
<script src="{{ asset('asset/vendor/pickadate/picker.js')}}"></script>
<script src="{{ asset('asset/vendor/pickadate/picker.time.js')}}"></script>
<script src="{{ asset('asset/vendor/pickadate/picker.date.js')}}"></script>

<!-- clockpicker -->
<script src="{{ asset('asset/vendor/clockpicker/js/bootstrap-clockpicker.min.js')}}"></script>
<script src="{{ asset('asset/vendor/pickadate/picker.time.js')}}"></script>
<!-- Clockpicker init -->
<script src="{{ asset('asset/js/plugins-init/clock-picker-init.js')}}"></script>
<!-- Daterangepicker -->
<script src="{{ asset('asset/js/plugins-init/bs-daterange-picker-init.js')}}"></script>

<script>
//Date range picker
$('#reservation').daterangepicker()
$('#reservation').on('apply.daterangepicker', function(ev, picker) {
  console.log(picker.startDate.format('YYYY-MM-DD'));
  console.log(picker.endDate.format('YYYY-MM-DD'));

  var startDate = function() {
    return picker.startDate.format('YYYY-MM-DD');    
  };
  var endDate = function() {
    return picker.endDate.format('YYYY-MM-DD');    
  };
  document.getElementById('start_date').value = startDate();
  document.getElementById('end_date').value = endDate();

});

$('#reservation2').daterangepicker()
$('#reservation2').on('apply.daterangepicker', function(ev, picker) {
  console.log(picker.startDate.format('YYYY-MM-DD'));
  console.log(picker.endDate.format('YYYY-MM-DD'));

  var startDate = function() {
    return picker.startDate.format('YYYY-MM-DD');    
  };
  var endDate = function() {
    return picker.endDate.format('YYYY-MM-DD');    
  };
  document.getElementById('start_date').value = startDate();
  document.getElementById('end_date').value = endDate();

});

$('#reservation3').daterangepicker()
$('#reservation3').on('apply.daterangepicker', function(ev, picker) {
  console.log(picker.startDate.format('YYYY-MM-DD'));
  console.log(picker.endDate.format('YYYY-MM-DD'));

  var startDate = function() {
    return picker.startDate.format('YYYY-MM-DD');    
  };
  var endDate = function() {
    return picker.endDate.format('YYYY-MM-DD');    
  };
  document.getElementById('start_date').value = startDate();
  document.getElementById('end_date').value = endDate();

});
</script>

<script type="text/javascript">
  $(function() {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });

    @if ($message = Session::get('flash-store'))
      toastr.success('{{ $message }}')
    @endif

    @if ($message = Session::get('flash-update'))
      toastr.success('{{ $message }}')
    @endif

    @if ($message = Session::get('flash-destroy'))
      toastr.success('{{ $message }}')
    @endif
  });
</script>
@yield('js')
