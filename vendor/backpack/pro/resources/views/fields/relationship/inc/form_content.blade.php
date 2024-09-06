<input type="hidden" name="_http_referrer" value={{ old('_http_referrer') ?? \URL::previous() ?? url($crud->route) }}>

{{-- See if we're using tabs --}}
@if ($crud->tabsEnabled() && count($crud->getTabs()))
    @include($crud->getFirstFieldView('relationship.inc.show_tabbed_fields'))
    <input type="hidden" name="_current_tab" value="{{ Str::slug($crud->getTabs()[0], "") }}" />
@else
  <div class="card">
    <div class="card-body row">
      @include($crud->getFirstFieldView('relationship.inc.show_fields'), ['fields' => $crud->fields()])
    </div>
  </div>
@endif

@foreach (app('widgets')->toArray() as $currentWidget)
@php
    $currentWidget = \Backpack\CRUD\app\Library\Widget::add($currentWidget);
@endphp
    @if($currentWidget->getAttribute('inline'))
        @include($currentWidget->getFinalViewPath(), ['widget' => $currentWidget->toArray()])
    @endif
@endforeach


