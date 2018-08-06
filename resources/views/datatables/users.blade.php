@extends('layouts.global')

@section('content')
<table class="table table-bordered" id="users-table">
  <thead>
      <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Username</th>
          <th>Status</th>
          <th>Actions</th>
      </tr>
  </thead>
</table>  
@endsection

@section('footer-scripts')
  <!-- jQuery -->
  <script src="//code.jquery.com/jquery.js"></script>
  <!-- DataTables -->
  <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
  <!-- Bootstrap JavaScript -->
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <!-- App scripts -->

  <script>
    $(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('datatables.data') !!}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'username', name: 'username' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });
  </script>
@endsection