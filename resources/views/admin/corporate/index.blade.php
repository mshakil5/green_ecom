@extends('admin.layouts.admin')

@section('content')



<!-- Main content -->
<section class="content pt-3" id="addThisFormContainer">

  <div class="container-fluid">
    <div class="row justify-content-md-center">
      <!-- right column -->
      <div class="col-md-12">
        <!-- general form elements disabled -->

    @if(session()->has('success'))
        <div class="alert alert-success pt-3 mb-3" id="successMessage">{{ session()->get('success') }}</div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger pt-3 mb-3" id="errMessage">{{ session()->get('error') }}</div>
    @endif

        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title">Corporate</h3>
          </div>
          
 

          <!-- /.card-header -->
          <div class="card-body">
            <div class="ermsg"></div>
            <form id="createThisForm" action="{{ route('admin.corporateupdate') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="hidden" class="form-control" id="codeid" name="codeid" value="{{$data->id}}">
              <div class="row">

                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{$data->title}}">
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                    <label>Page Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror summernote" cols="30" rows="3">{!! $data->description !!}</textarea>
                    </div>
                </div>

                
                

                
              </div>
            
          </div>

          
          <!-- /.card-body -->
          <div class="card-footer">
            <button type="submit" class="btn btn-secondary" value="Update">Update</button>
          </div>
        </form>
          <!-- /.card-footer -->
          <!-- /.card-body -->
        </div>
      </div>
      <!--/.col (right) -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->


@endsection
@section('script')

<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '26', '28', '30', '32', '34', '36', '38', '40', '42', '44', '46', '48', '50', '52', '54', '56', '58', '60', '62', '64', '66', '68', '70', '72']
        });
    });
</script>

<script>
function previewImage(event, previewId) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById(previewId);
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>


@endsection