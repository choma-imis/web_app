<!-- Last Modified Date: 19-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)   -->
@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')
@include('layouts.components.error-list')
@include('layouts.components.success-alert')
@include('layouts.components.error-alert')

<div class="card card-info">
    {!! Form::open(['url' => action('Language\LanguageController@import_translates', ['id' => $id]), 'files' => true, 'class' => 'form-horizontal']) !!}
        <div class="card-body">
            <div class="form-group row required">
                {!! Form::label('Upload Translation File',null,['class' => 'col-sm-3 control-label', 'style'=>'padding-top:3px;']) !!}
                <div class="col-sm-3">
                {!! Form::file('csvfile', ['id' => 'csvfile', 'onchange' => 'validateFileExtension(this, "fileHint", "csv")']) !!}
                <small id="fileHint">Please select a CSV file.</small> <br>
                <small id="fileHeaderHint">Please ensure CSV file headers match with provided template.</small>
                </div>
            </div>
        </div><!-- /.card-body -->
        <div class="card-footer">
        <a href="{{ action('Language\LanguageController@index') }}" class="btn btn-info">Back to List</a>
            {!! Form::submit('Upload', ['class' => 'btn btn-info']) !!}
        </div><!-- /.card-footer -->
    {!! Form::close() !!}
</div><!-- /.card -->

@stop


@push('scripts')


@endpush
