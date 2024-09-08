<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token()}}"/>
		<title>Laravel Shop :: Administrative Panel</title>
		<!-- Google Font: Source Sans Pro -->
		@include('admin.layouts.components.css')
	</head>
	<body class="hold-transition sidebar-mini">
		<!-- Site wrapper -->
		<div class="wrapper">
			<!-- Navbar -->
			@include('admin.layouts.components.header')
			<!-- /.navbar -->
			<!-- Main Sidebar Container -->
			@include('admin.layouts.components.sidebar')
			<!-- Content Wrapper. Contains page content -->
                @yield('content')
			<!-- /.content-wrapper -->
			<footer class="main-footer">
				<strong>Copyright &copy; 2014-2022 AmazingShop All rights reserved.
			</footer>

		</div>
		<!-- ./wrapper -->
		<!-- jQuery -->

		@include('admin.layouts.components.js')

        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        //     $(document).ready(function() {
        //   $(".summernote").summernote({
        //     height: 250
        //   });
        // });
        </script>

        @yield('customjs')
	</body>
</html>
