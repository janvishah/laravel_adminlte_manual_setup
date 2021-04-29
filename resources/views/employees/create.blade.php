@extends('layouts.app')

@section('title', __('Create Trade'))

@push('css_after')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<style>
    #measurements {
        counter-reset: measurements;
    }

    .block-header a span.counter::before {
        counter-increment: measurements;
        content: "#" counter(measurements) ": ";
    }
</style>
@endpush

@section('content')
    <div class="card-header">
        <h3 class="card-title">Add New Customer</h3>
    </div>
  <!-- /.card-header -->
  <div class="card-body">
        {!! Form::open(['method' => 'POST', 'route' => ['customers.store'], 'files' => true]) !!}
            <fieldset>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('name', 'Name', ['class' => 'required']) !!}
                            {!! Form::text('name', '', ['placeholder' => 'Ex: Jhon', 'class' => 'form-control form-control-alt']); !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('email', 'Email') !!}
                            {!! Form::text('email', '', ['placeholder' => 'Ex: jhon@gmail.com', 'class' => 'form-control form-control-alt']); !!}
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

            <fieldset id="measurements">
                <legend>Measurements:</legend>
                <div id="measurement_accordion" role="tablist" aria-multiselectable="true"></div>
                <div class="row float-right mt-2">
                    <div class="col">
                        <button type="button" class="btn btn-alt-secondary text-primary btn-sm add-row"><i class="fa fa-plus"></i> Add Measurements</button>
                    </div>
                </div>
            </fieldset>
                
            <div class="btn-group-horizontal btn-group">
                <button type="submit" class="btn btn-primary ajax-submit"><i class="fa fa-save mr-1"></i>Save</button>
                <a href="{{ route('customers.index') }}" class="btn btn-outline-primary"><i class="fa fa-times mr-1"></i>Cancel</a>
            </div>
        {!! Form::close() !!}
    </div>
</div>

<template id="measurement_accordion_item_template">
    <div class="block block-rounded mb-1">
        <div class="block-header block-header-default" role="tab" id="measurement_accordion_h1">
            <a class="font-w600" data-toggle="collapse" data-parent="#measurement_accordion" href="#measurement_accordion_q%d%" aria-expanded="true" aria-controls="measurement_accordion_q%d%"><span class="counter"></span> <span class="measurement-title"></span></a>
            <div class="block-options">
                <button type="button" class="btn-block-option remove-row">
                    <i class="far fa-fw fa-trash-alt"></i>
                </button>
            </div>
        </div>
        <div id="measurement_accordion_q%d%" class="collapse show" role="tabpanel" aria-labelledby="measurement_accordion_h1">
            <div class="block-content">
                <div class="form-group">
                    {!! Form::label('Height', 'Height', ['class' => 'required']) !!}
                    {!! Form::text('measurement[%d%][height]', '', ['placeholder' => 'Ex: 150', 'class' => 'measurement-title-field form-control form-control-alt', 'id' => 'measurement-%d%-title']); !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Weight') !!}
                    {!! Form::text('measurement[%d%][weight]', '', ['placeholder' => '50', 'class' => 'form-control form-control-alt', 'id' => 'measurement-%d%-icon']); !!}
                </div>
               
            </div>
        </div>
    </div>
</template>
@endsection

@push('js_after')
<script>
    jQuery(document).ready(function(){
        let measurements_count = 1;
        jQuery('#measurements').on('click', '.add-row', function() {
            let template = jQuery('#measurement_accordion_item_template').html().replace(/%d%/gi, measurements_count)
            jQuery('#measurement_accordion').append(template)
            measurements_count++
        })
        jQuery('#measurements').on('click', '.remove-row', function() {
            if (jQuery('#measurements .block').length > 1) {
                jQuery(this).parents('.block:first').remove()
            } else {
                alert('minimum one measurement is required')
            }
        })

        jQuery('#measurements .add-row').trigger('click')

        jQuery(document).on('change', '.measurement-title-field', function() {
            let title = this.value;
            jQuery(this).parents('.block:first').find('.measurement-title').html(title);
        })
    })
</script>
@endpush
