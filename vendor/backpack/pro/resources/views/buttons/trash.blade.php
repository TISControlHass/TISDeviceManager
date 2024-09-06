@if ($crud->hasAccess('trash', $entry) && $entry->trashed() === false)
	<a href="javascript:void(0)" onclick="trashEntry(this)" data-route="{{ url($crud->route.'/'.$entry->getKey().'/trash') }}" class="btn btn-sm btn-link" data-button-type="trash"><i class="la la-trash"></i> {{ trans('backpack/pro::trash.trash') }}</a>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@loadOnce('trash_button_script')
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>

	if (typeof trashEntry != 'function') {
	  $("[data-button-type=trash]").unbind('click');

	  function trashEntry(button) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		var route = $(button).attr('data-route');

		swal({
		  title: "{!! trans('backpack::base.warning') !!}",
		  text: "{!! trans('backpack/pro::trash.trash_confirm') !!}",
		  icon: "warning",
		  buttons: ["{!! trans('backpack::crud.cancel') !!}", "{!! trans('backpack/pro::trash.trash') !!}"],
		  dangerMode: true,
		}).then((value) => {
			if (value) {
				$.ajax({
			      url: route,
			      type: 'DELETE',
			      success: function(result) {
			          if (result == 1) {
						  // Redraw the table
						  if (typeof crud != 'undefined' && typeof crud.table != 'undefined') {
							  // Move to previous page in case of deleting the only item in table
							  if(crud.table.rows().count() === 1) {
							    crud.table.page("previous");
							  }

							  crud.table.draw(false);
						  }

			          	  // Show a success notification bubble
			              new Noty({
		                    type: "success",
		                    text: "{!! '<strong>'.trans('backpack/pro::trash.trash_confirmation_title').'</strong><br>'.trans('backpack/pro::trash.trash_confirmation_message') !!}"
		                  }).show();

			              // Hide the modal, if any
			              $('.modal').modal('hide');
			          } else {
			              // if the result is an array, it means
			              // we have notification bubbles to show
			          	  if (result instanceof Object) {
			          	  	// trigger one or more bubble notifications
			          	  	Object.entries(result).forEach(function(entry, index) {
			          	  	  var type = entry[0];
			          	  	  entry[1].forEach(function(message, i) {
					          	  new Noty({
				                    type: type,
				                    text: message
				                  }).show();
			          	  	  });
			          	  	});
			          	  } else {// Show an error alert
				              swal({
				              	title: "{!! trans('backpack/pro::trash.trash_confirmation_not_title') !!}",
	                            text: "{!! trans('backpack/pro::trash.delete_confirmation_not_message') !!}",
				              	icon: "error",
				              	timer: 4000,
				              	buttons: false,
				              });
			          	  }
			          }
			      },
			      error: function(result) {
			          // Show an alert with the result
			          swal({
		              	title: "{!! trans('backpack/pro::trash.trash_confirmation_not_title') !!}",
                        text: "{!! trans('backpack/pro::trash.trash_confirmation_not_message') !!}",
		              	icon: "error",
		              	timer: 4000,
		              	buttons: false,
		              });
			      }
			  });
			}
		});

      }
	}

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('trashEntry');
</script>
@if (!request()->ajax()) @endpush @endif
@endLoadOnce