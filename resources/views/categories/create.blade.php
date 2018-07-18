@extends('layouts.global')

@section('title') Create Category @endsection 

@section('content')

<div class="col-md-8">

  @if(session('status'))
    <div class="alert alert-success">
      {{session('status')}}
    </div>
  @endif 

  <form 
    enctype="multipart/form-data" 
    class="bg-white shadow-sm p-3" 
    action="{{route('categories.store')}}" 
    method="POST">

    @csrf

    <label>Category name</label><br>
    <input 
      type="text" 
      class="form-control" 
      name="name">
    <br>
    
    <label>Category image</label>
    <input 
      type="file" 
      class="form-control"
      name="image">
    
    <br>

    <input 
      type="submit" 
      class="btn btn-primary" 
      value="Save">

  </form>
</div>

@endsection