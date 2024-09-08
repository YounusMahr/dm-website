@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update subcategory</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('subcategory.show') }}" class="btn btn-primary">Back</a>
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
            <form action="" method="post" id="subcategoryform" enctype="multipart/form-data">
          <div class="card">
              <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Select Category</label>
                        <select name="category" id="category" class="form-control">
                            @if($categorys->isNotEmpty())
                                @foreach ($categorys as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $subcategory->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <p></p>
                    </div>

                      <div class="col-md-6">
                          <div class="mb-3">
                              <label for="name">Name</label>
                              <input type="text" name="name" id="name" class="form-control name @error('name') is-invalid @enderror" value="{{ $subcategory->name }}" placeholder="Name">
                              <p></p>
                              @error('name')
                          <p class="invalid-feedback">{{ $message }}</p>

                          @enderror
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="mb-3">
                              <label for="slug">Slug</label>
                              <input type="text" name="slug" id="slug" class="form-control slug @error('email') is-invalid @enderror"
                              value="{{ $subcategory->slug }}"
                              placeholder="Email">
                              <p></p>
                              @error('email')
                          <p class="invalid-feedback">{{ $message }}</p>

                          @enderror
                          </div>
                      </div>

                      <div class="col-md-6">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image" class="form-control  image">
                        <p></p>
                       </div>

                      <div class="col-md-6">
                              <label for="name">Status</label>
                             <select class="form-control" name="status" id="status">
                                <option {{ ($subcategory->status == 1) ? 'selected' : '' }} value="1">Active</option>
                                <option {{ ($subcategory->status == 0) ? 'selected' : '' }} value="0">Inactive</option>
                             </select>
                      </div>
                      <div class="col-md-6">
                        <label for="name">Show on Home</label>
                       <select class="form-control" name="showhome" id="status">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                       </select>
                </div>

                  </div>
              </div>
          </div>
          <div class="pb-5 pt-3">
              <button type="submit" class="btn btn-primary">Update</button>
              <a href="{{ route('subcategory.show') }}" class="btn btn-outline-dark ml-3">Cancel</a>
          </div>
           </form>
      </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>

@section('customjs')
<script>
$("#subcategoryform").submit(function(event) {
    event.preventDefault();
    var element = $(this);
    var formData = new FormData();

    // Append the form fields to the FormData object
    $.each(element.serializeArray(), function(i, field) {
        formData.append(field.name, field.value);
    });

    // Append the image file to the FormData object
    var image = element.find('input[type="file"]')[0].files[0];
    formData.append('image', image);

    $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ route('subcategory.update', $subcategory->id) }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            // Enable submit button (if necessary)
            $("button[type=submit]").prop('disabled', false);

            if (response["status"] === true) {
                window.location.href = "{{ route('subcategory.show') }}";
                // Clear any previous error messages
                $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $('#image').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $('#category').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                // Optionally, you can redirect or reset the form here
                element.trigger('reset'); // Reset the form if needed
            } else {
                var errors = response['errors'];

                if (errors['name']) {
                    $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name'][0]);
                } else {
                    $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }

                if (errors['slug']) {
                    $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug'][0]);
                } else {
                    $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }

                if (errors['image']) {
                    $('#image').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['image'][0]);
                } else {
                    $('#image').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }

                if (errors['category']) {
                    $('#image').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['category'][0]);
                } else {
                    $('#category').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }
            }
        },
        error: function(jqXHR, exception) {
            console.log('Something went wrong');
        }
    });
});


//    $("#subcategoryform").submit(function(event){
//     event.preventDefault();
//     var element = $(this);
//     $("button[type=submit]").prop('disabled', true);
//     $.ajax({
//         url: '{{ route('subcategory.update', $subcategory->id) }}',
//         type: 'POST',
//         data: element.serializeArray(),
//         dataType: 'json',
//         success: function(response){
//             // Enable submit button (if necessary)
//             $("button[type=submit]").prop('disabled', false);

//             if(response["status"] === true){
//                 window.location.href="{{ route('subcategory.show') }}"
//                 // Clear any previous error messages
//                 $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
//                 $('#email').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");

//                 // Optionally, you can redirect or reset the form here
//                 element.trigger('reset'); // Reset the form if needed
//             } else {
//                 var errors = response['errors'];
//                 if(response["notFound"] == true ){
//         window.location.href="{{ route('subcategory.show') }}"
//       }
//                 if(errors['name']){
//                     $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name'][0]);
//                 } else {
//                     $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
//                 }

//                 if(errors['email']){
//                     $('#email').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['email'][0]);
//                 } else {
//                     $('#email').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
//                 }
//             }
//         },
//         error: function(jqXHR, exception){
//             console.log('Something went wrong');
//         }
//     });
// });

// ----- slug generation code ------
$(".name").change(function(){
    console.log('changing');
    let element = $(this);
    $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ route("subcategory.slug") }}',
        type: 'get',
        data: {name: element.val()},
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled', false);
            if(response['status'] === true){
                console.log('success');
                $('.slug').val(response["slug"]);
            }
        }
    });
});
</script>

@endsection


@endsection

