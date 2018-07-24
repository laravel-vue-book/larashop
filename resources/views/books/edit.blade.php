@extends('layouts.global')

@section('title') Edit book @endsection 

@section('content')

<div class="row">
  <div class="col-md-8">

    @if(session('status'))
      <div class="alert alert-success">
        {{session('status')}}
      </div>
    @endif

    <form
      enctype="multipart/form-data"
      method="POST"
      action="{{route('books.update', ['id' => $book->id])}}"
      class="p-3 shadow-sm bg-white"
    >

    @csrf 
    <input type="hidden" name="_method" value="PUT">

    <label for="title">Title</label><br>
    <input
      type="text"
      class="form-control"
      value="{{$book->title}}"
      name="title"
      placeholder="Book title"
    />
    <br>

    <label for="cover">Cover</label><br>
    <small class="text-muted">Current cover</small><br>
    @if($book->cover)
      <img src="{{asset('storage/' . $book->cover)}}" width="96px"/>
    @endif
    <br><br>
    <input 
      type="file" 
      class="form-control"
      name="cover"
    >
    <small class="text-muted">Kosongkan jika tidak ingin mengubah cover</small>
    <br><br>


    <label for="slug">Slug</label><br>
    <input 
      type="text"
      class="form-control"
      value="{{$book->slug}}"
      name="slug"
      placeholder="enter-a-slug"
    />
    <br>

    <label for="description">Description</label> <br>
    <textarea name="description" id="description" class="form-control">{{$book->description}}</textarea>
    <br>

    <label for="categories">Categories</label>
    <select multiple class="form-control" name="categories" id="categories"></select>
    <br>
    <br>

    <label for="stock">Stock</label><br>
    <input type="text" class="form-control" placeholder="Stock" id="stock" name="stock" value="{{$book->stock}}">
    <br>

    <label for="author">Author</label>
    <input placeholder="Author" value="{{$book->author}}" type="text" id="author" name="author" class="form-control">
    <br>

    <label for="publisher">Publisher</label><br>
    <input class="form-control" type="text" placeholder="Publisher" name="publisher" id="publisher" value="{{$book->publisher}}">
    <br>

    <label for="price">Price</label><br>
    <input type="text" class="form-control" name="price" placeholder="Price" id="price" value="{{$book->price}}">
    <br>

    <label for="">Status</label>
    <select name="status" id="status" class="form-control">
      <option {{$book->status == 'PUBLISH' ? 'selected' : ''}} value="PUBLISH">PUBLISH</option>
      <option {{$book->status == 'DRAFT' ? 'selected' : ''}} value="DRAFT">DRAFT</option>
    </select>
    <br>
    
    <button class="btn btn-primary" value="PUBLISH">Update</button>

    </form>
  </div>
</div>

@endsection



@section('footer-scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
$('#categories').select2({
  ajax: {
    url: 'http://larashop.test/ajax/categories/search',
    processResults: function(data){
      return {
        results: data.map(function(item){return {id: item.id, text: item.name} })
      }
    }
  }
});

var categories = {!! $book->categories !!}

    categories.forEach(function(category){
      var option = new Option(category.name, category.id, true, true);
      $('#categories').append(option).trigger('change');
    });
</script>
@endsection