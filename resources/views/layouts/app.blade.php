@include('layouts.head')
<body>
@include('layouts.navbar')
@yield('content')
@include('layouts.footer')

<!-- Placed at the end of the document so the pages load faster -->
<script src="{{asset('https://code.jquery.com/jquery-3.2.1.slim.min.js')}}"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js')}}"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
<script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js')}}"
        integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
        crossorigin="anonymous"></script>
<script src="{{asset('bjs/bootstrap.min.js')}}"></script>
<script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js')}}"></script>

<script src="{{asset('js/app.js')}}"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script src="{{asset('https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js')}}"></script>
<link href="{{asset('https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
<script src="{{asset('https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
@yield("script")
</body>
