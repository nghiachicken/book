@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Book Management</h2>
        </div>
        <div class="col-md-12" style="padding-left: 0px">
            <form method="POST" id="search" class="form-inline" role="form">
                <div class="col-md-4" style="padding-left: 0px">
                    <div class="form-group" style="width: 100%">
                        <label for="sel1">Name</label>
                        <input type="text" name="name_book" style="width: 100%" class="form-control" id="name_book">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" style="width: 100%">
                        <label for="sel1">Categories</label>
                        <select class="form-control select2" id="select_category" style="width: 100%" name="select_status">
                            <option value="0">All</option>
                            @if($categories)
                            @foreach($categories as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-2" style="margin-top: 25px;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
        <div class="pull-right" style="padding-bottom: 10px">
        @can('book-create')
            <a class="btn btn-success" href="{{ route('books.create') }}"> Create New Book</a>
            @endcan
        </div>
    </div>
</div>


@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif


<table class="table table-striped table-bordered data-table">
    <thead>
      <tr>
         <th>ID</th>
         <th>Tên Sách</th>
         <th>Bìa Sách</th>
         <th>Thể Loại</th>
         <th width="280px">Thao Tác</th>
      </tr>
    </thead>
    <tbody>
    {{-- @if(isset($books) && $books)
    @foreach ($books as $key => $book)
    <tr>
        <td>{{ $book->id }}</td>
        <td>{{ $book->name }}</td>
        <td>
        @if($book->image)
        <img src="{{$book->image}}" alt="" style="height: 150px;width: auto;">
        @endif    
        </td>
        <td>{{ get_category($book) }}</td>
        <td>
            @can('book-edit')
                <a class="btn btn-primary" href="{{ route('books.edit',$book->id) }}">Edit</a>
            @endcan
            @can('book-delete')
                {!! Form::open(['method' => 'DELETE','route' => ['books.destroy', $book->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            @endcan
        </td>
    </tr>
    @endforeach
    @endif --}}
    </tbody>
</table>


{{-- {!! isset($books) && $books ? $books->render() : null !!} --}}


@endsection
@push('scripts')
<script>
    // $(".data-table").dataTable();
    var oTable = $('.data-table').DataTable({
            processing:true,
            serverSide:true,
            pageLength: 10,
            ajax: {
            url: '{{ route('data_book') }}',
            data: function (d) {
                d.name_book = $('input[name=name_book]').val();
                d.select_category = $('select[name=select_status]').val();
                console.log(d);
                }
            },
            columns: [
                    { data: 'id',searchable:true},
                    { data: 'name',searchable:true},
                    { data: 'image',searchable:false},
                    { data: 'categories',searchable:true},
                    { data: 'button',searchable:false}
                ],
            order: [ [0, 'desc'] ],
            });
        $("#search").on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
        });
</script>
@endpush
