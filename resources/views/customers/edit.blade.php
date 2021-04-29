@extends('layouts.app')

@section('title', __('Update Customer Info'))

@push('css_after')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<style>
    #masterclasses {
        counter-reset: masterclasses;
    }

    .block-header a span.counter::before {
        counter-increment: masterclasses;
        content: "#" counter(masterclasses) ": ";
    }
</style>
@endpush

@section('content')
    <div class="card-header">
        <h3 class="card-title">Update Customer</h3>
    </div>
  <!-- /.card-header -->
    <div class="card-body">
        {!! Form::open(['method' => 'PUT', 'route' => ['customers.update',$customer->id], 'files' => true]) !!}
            <fieldset>
                <legend>Trade Information:</legend>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('name', 'Name', ['class' => 'required']) !!}
                            {!! Form::text('name', $customer->name, ['disabled' => true, 'placeholder' => 'Ex: Jhon', 'class' => 'form-control form-control-alt']); !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('email', 'Email') !!}
                            {!! Form::text('email', $customer->email, ['disabled' => true, 'placeholder' => 'Ex: jhon@gmail.com', 'class' => 'form-control form-control-alt']); !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('password', 'Password') !!}
                            {!! Form::password('password', ['class' => 'form-control form-control-alt']); !!}
                        </div>
                    </div>
                </div>
            </fieldset>
                
            <div class="btn-group-horizontal btn-group">
                <button type="submit" class="btn btn-primary ajax-submit"><i class="fa fa-save mr-1"></i>Update</button>
                <a href="{{ route('customers.index') }}" class="btn btn-outline-primary"><i class="fa fa-times mr-1"></i>Cancel</a>
            </div>
        {!! Form::close() !!}
    </div>
</div>

@endsection