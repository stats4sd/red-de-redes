@if ($crud->hasAccess('markasreviewed'))
  @if ($entry->is_marked_as_reviewed)
    <a href="javascript:void(0)" onclick="markAsReviewed(this)" data-route="{{ url($crud->route.'/'.$entry->getKey().'/markasreviewed') }}" class="btn btn-primary disabled" data-button-type="markasreviewed"><i class="la"></i> Mark as Reviewed</a>
  @else
    <a href="javascript:void(0)" onclick="markAsReviewed(this)" data-route="{{ url($crud->route.'/'.$entry->getKey().'/markasreviewed') }}" class="btn btn-primary" data-button-type="markasreviewed"><i class="la"></i> Mark as Reviewed</a>
  @endif  
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>
    if (typeof markAsReviewed != 'function') {
      $("[data-button-type=markasreviewed]").unbind('click');

      function markAsReviewed(button) {
          // ask for confirmation before deleting an item
          // e.preventDefault();
          var button = $(button);
          var route = button.attr('data-route');

          $.ajax({
              url: route,
              type: 'POST',
              success: function(result) {
                  // Show an alert with the result
                  new Noty({
                    type: "success",
                    text: "<strong>Entry has been marked as reviewed successfully</strong>"
                  }).show();

                  // Hide the modal, if any
                  $('.modal').modal('hide');

                  if (typeof crud !== 'undefined') {
                    crud.table.ajax.reload();
                  }
              },
              error: function(result) {
                  // Show an alert with the result
                  new Noty({
                    type: "warning",
                    text: "<strong>Failed to mark entry as reviewed</strong>"
                  }).show();
              }
          });
      }
    }

    // make it so that the function above is run after each DataTable draw event
    // crud.addFunctionToDataTablesDrawEventQueue('cloneEntry');
</script>
@if (!request()->ajax()) @endpush @endif