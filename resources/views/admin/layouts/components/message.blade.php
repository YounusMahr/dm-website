
@if (Session::has('error'))
<div class="alert alert-danger">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
<h4><i class="fa f-ban"></i>Error!</h4>{{ Session::get('error') }}
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    <h4><i class="fa f-check"></i>Success!</h4>{{ Session::get('success') }}
    </div>
@endif
