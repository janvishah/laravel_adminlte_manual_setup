
@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')

  <div class="card-header">
    <h3 class="card-title">List of Employees</h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <div class="btn-group-horizontal btn-group">
            <a href="{{ route('customers.create') }}" class="btn btn-outline-primary">Create New User </a>
        </div>
  </div>
  <!-- /.card-body -->

@endsection




@push('css_after')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endpush

@push('js_after') 
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('employees.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false, sClass : 'text-center' },
                ]
            });
            
        });

        
    </script>
@endpush
