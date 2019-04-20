@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Book</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('books.index') }}"> Back</a>
        </div>
    </div>
</div>


@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif



{!! Form::model($book, ['method' => 'PATCH','route' => ['books.update', $book->id],'enctype' => 'multipart/form-data']) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {!! Form::text('name', $book->name, array('placeholder' => '','class' => 'form-control')) !!}
        </div>
    </div>
    
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Categories:</strong>
            {!! Form::select('categories[]', $categories,$category_book, array('class' => 'form-control','multiple')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Image:</strong>
            <input type="file" class="form-control" name="image" accept="image/*" id="imgInp" onchange="preview_image(event)">
            @if($book->image)
            <img id="image" src="{{$book->image}}" style="width: 200px ; height: auto;padding-top: 10px"  alt="" />
            @endif
            <img id="output_image" style="width: 200px ; height: auto;padding-top: 10px"  alt="" />
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
{!! Form::close() !!}


@endsection
@push('scripts')
<script>
    function preview_image(event) 
    {
      $("#image").hide();
      var reader = new FileReader();
      reader.onload = function()
      {
      var output = document.getElementById('output_image');
      output.src = reader.result;
      }
      reader.readAsDataURL(event.target.files[0]);
    }

</script>
@endpush