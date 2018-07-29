@extends('layouts.global')

@section('title') Books list @endsection 

@section('content') 
  <div class="row">
    <div class="col-md-12">
      @if(session('status'))
        <div class="alert alert-success">
          {{session('status')}}
        </div>
      @endif

      <div class="row">
          <div class="col-md-6">
            <form
              action="{{route('books.index')}}"
            >

            <div class="input-group">
                <input name="keyword" type="text" value="{{Request::get('keyword')}}" class="form-control" placeholder="Filter by title">
                <div class="input-group-append">
                  <input type="submit" value="Filter" class="btn btn-primary">
                </div>
            </div>

            </form>
          </div>
          <div class="col-md-6">
            <ul class="nav nav-pills card-header-pills">
              <li class="nav-item">
                <a class="nav-link {{Request::get('status') == NULL && Request::path() == 'books' ? 'active' : ''}}" href="{{route('books.index')}}">All</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link {{Request::get('status') == 'publish' ? 'active' : '' }}" href="{{route('books.index', ['status' => 'publish'])}}">Publish</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{Request::get('status') == 'draft' ? 'active' : '' }}" href="{{route('books.index', ['status' => 'draft'])}}">Draft</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{Request::path() == 'books/trash' ? 'active' : ''}}" href="{{route('books.trash')}}">Trash</a>
              </li>
            </ul>
          </div>
        </div>

      <hr class="my-3">

      <div class="row mb-3">
        <div class="col-md-12 text-right">
          <a
            href="{{route('books.create')}}"
            class="btn btn-primary"
          >Create book</a>
        </div>
      </div>

      <table class="table table-bordered table-stripped">
        <thead>
          <tr>
            <th><b>Cover</b></th>
            <th><b>Title</b></th>
            <th><b>Author</b></th>
            <th><b>Status</b></th>
            <th><b>Categories</b></th>
            <th><b>Stock</b></th>
            <th><b>Price</b></th>
            <th><b>Action</b></th>
          </tr>
        </thead>
        <tbody>
          @foreach($books as $book)
            <tr>
              <td>
                @if($book->cover)
                  <img src="{{asset('storage/' . $book->cover)}}" width="96px"/>
                @endif
              </td>
              <td>{{$book->title}}</td>
              <td>{{$book->author}}</td>
              <td>
                @if($book->status == "DRAFT")
                  <span class="badge bg-dark text-white">{{$book->status}}</span>
                @else 
                  <span class="badge badge-success">{{$book->status}}</span>
                @endif 
              </td>
              <td>
                <ul class="pl-3">
                @foreach($book->categories as $category)
                  <li>{{$category->name}}</li>  
                @endforeach
                </ul>
              </td>
              <td>{{$book->stock}}</td>
              <td>{{$book->price}}</td>
              <td>
                  <a
                   href="{{route('books.edit', ['id' => $book->id])}}"
                   class="btn btn-info btn-sm"
                  > Edit </a>

                  <form
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Move book to trash?')"
                    action="{{route('books.destroy', ['id' => $book->id ])}}"
                  >

                  @csrf 
                  <input 
                    type="hidden" 
                    value="DELETE"
                    name="_method">

                  <input 
                    type="submit" 
                    value="Trash" 
                    class="btn btn-danger btn-sm">

                  </form>

              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td colspan="10">
              {{$books->appends(Request::all())->links()}}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
@endsection