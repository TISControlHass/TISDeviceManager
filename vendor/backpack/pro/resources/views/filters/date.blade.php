{{-- Date Range Backpack CRUD filter --}}
<li filter-name="{{ $filter->name }}"
    filter-type="{{ $filter->type }}"
    filter-key="{{ $filter->key }}"
	class="nav-item dropdown {{ Request::get($filter->name)?'active':'' }}">
	<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $filter->label }} <span class="caret"></span></a>
	<div class="dropdown-menu p-0">
		<div class="form-group backpack-filter mb-0">
			<div class="input-group date">
                <span class="input-group-text"><i class="la la-calendar"></i></span>
		        <input class="form-control pull-right"
		        		id="datepicker-{{ $filter->key }}"
		        		type="text"
						@if ($filter->currentValue)
							value="{{ $filter->currentValue }}"
						@endif
		        		>
                <a class="input-group-text datepicker-{{ $filter->key }}-clear-button" href=""><i class="la la-times"></i></a>
		    </div>
		</div>
	</div>
</li>

{{-- ########################################### --}}
{{-- Extra CSS and JS for this particular filter --}}

{{-- FILTERS EXTRA CSS --}}
{{-- push things in the after_styles section --}}

@push('crud_list_styles')
    @basset('https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css')
	<style>
		.input-group.date {
			width: 320px;
			max-width: 100%;
		}
	</style>
@endpush


{{-- FILTERS EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('crud_list_scripts')
	{{-- include select2 js --}}
	@basset('https://unpkg.com/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js')
	@php $language = $filter->options['language'] ?? \App::getLocale(); @endphp
	@if ($language !== 'en')
	@basset('https://unpkg.com/bootstrap-datepicker@1.9.0/dist/locales/bootstrap-datepicker.'.$language.'.min.js', true, ['charset' => 'UTF-8'])
	@endif
  <script>
		jQuery(document).ready(function($) {
            var shouldUpdateUrl = false;
			var dateInput = $('#datepicker-{{ $filter->key }}').datepicker({
				autoclose: true,
				format: 'yyyy-mm-dd',
				todayHighlight: true,
				language: '{{ $language }}',
			})
			.on('changeDate', function(e) {
				var d = new Date(e.date);

				if (isNaN(d.getFullYear())) {
					var value = '';
				} else {
					var value = d.getFullYear() + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" + ("0" + d.getDate()).slice(-2);
				}

				var parameter = '{{ $filter->name }}';

				var new_url = updateDatatablesOnFilterChange(parameter, value, value || shouldUpdateUrl);
				shouldUpdateUrl = false;

				// mark this filter as active in the navbar-filters
				if (URI(new_url).hasQuery('{{ $filter->name }}', true)) {
					$('li[filter-key={{ $filter->key }}]').removeClass('active').addClass('active');
				}
			});

			// open the datepicker when filter is open
			$('li[filter-key={{ $filter->key }}] a').on('click', function(e) {
				setTimeout(() => {
					dateInput.focus();
				}, 50);
			});

			$('li[filter-key={{ $filter->key }}]').on('filter:clear', function(e) {

				$('li[filter-key={{ $filter->key }}]').removeClass('active');
				$('#datepicker-{{ $filter->key }}').datepicker('update', '');
				$('#datepicker-{{ $filter->key }}').trigger('changeDate');
			});

			// datepicker clear button
			$(".datepicker-{{ $filter->key }}-clear-button").click(function(e) {
				e.preventDefault();
                shouldUpdateUrl = true;
				$('li[filter-key={{ $filter->key }}]').trigger('filter:clear');
			})
		});
  </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
