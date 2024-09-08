

@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper">
    {{-- ----- filter-section------ --}}

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Users</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('user.create') }}" class="btn btn-primary">New User</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        @include('admin.layouts.components.message')
        <div class="container-fluid">
            <div class="card">
                <div class="d-flex flex-row justify-content-between m-4 ">
                    <form class="d-flex" action="" method="get">
                        <div class="input-group text-white">
                          <input type="" value="{{ Request::get('keyword') }}" class="form-control" name="keyword" placeholder="Search...">
                            <button class="input-group-text bg-primary" type="submit"><i class="fas fa-search text-white pointer-event bx bx-search"></i></button>
                        </div>
                      </form>
                 <button type="button" class="btn bg-primary  rounded  " onclick="window.location.href='{{ route("user.show") }}'" style="color:white">Clear fitler</button>
                 </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th width="100">Status</th>
                                <th width="100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($users->isNotEmpty())
                            @foreach ($users->sortByDesc('created_at') as $index => $user)
                              <tr>
                                <td>
                                    {{ ($index % 3) + 1 }}.
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->status == 0)
                                    <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @endif

                                    @if($user->status == 1)
                                    <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('admin/user/edit/'.$user->id) }}">
                                        <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </a>
                                    <a style="cursor: pointer"
                                    onclick="deleteCategory({{ $user->id }})"
                                    class="text-danger w-4 h-4 mr-1">
                                        <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                          </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach


                            @else
                            <tr class="d-none d-md-table-row"> <!-- This hides the row on smaller screens and shows it on medium and larger screens -->
                                <td colspan="5" class="text-center">Record Not Found</td> <!-- Centering text and spanning across 5 columns -->
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination m-0 float-right">
                      <li>{{ $users->links() }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>


{{-- //------- custom js ------ --}}
@section('customjs')

<script>

function deleteCategory(id){
    var url = '{{ route("user.destroy","ID") }}';
    var newUrl = url.replace('ID',id);
    if(confirm('Are you want to Delete Record')){


    $.ajax({
        url: newUrl,
        type: 'get',
        data: {},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response){

      $("button[type=submit]").prop('disabled', false);

      if(response["status"] ){
        window.location.href="{{ route('user.show') }}"
      }
      else{
        console.log('failed')
      }

    }
});

}
}

</script>
@endsection

@endsection
