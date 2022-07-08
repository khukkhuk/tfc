<base href="{{ url('/th') }}">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta charset="utf-8">
<link href="{{asset("backend/dist/images/logo.svg")}}" rel="shortcut icon">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="">
<title>Backend System</title>
<link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.css" />
<link rel="stylesheet" href="{{asset("backend/dist/css/app.css")}}" />
<link rel="stylesheet" type="text/css" href="{{ asset('backend/libs/toastr/toastr.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('backend/dist/css/app.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('backend/dist/css/_app.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('backend/libs/fontawesome/icons.min.css')}}">
<!-- Sweet Alert-->
<link href="{{ asset('backend/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />



<style>
.swal2-container {
  z-index: 99999 !important;
}
</style>