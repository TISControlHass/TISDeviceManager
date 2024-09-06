@extends(backpack_view('errors.layout'))

@php
  $error_number = 500;
@endphp

@section('title')
  {{ trans('backpack::base.error_page.500') }}
@endsection

@section('description')
  {!! $exception?->getMessage() ? e($exception->getMessage()) : trans('backpack::base.error_page.message_500') !!}
@endsection
