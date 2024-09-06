@extends(backpack_view('errors.layout'))

@php
  $error_number = 503;
@endphp

@section('title')
  {{ trans('backpack::base.error_page.503') }}
@endsection

@section('description')
  {!! $exception?->getMessage() ? e($exception->getMessage()) : trans('backpack::base.error_page.message_503') !!}
@endsection
