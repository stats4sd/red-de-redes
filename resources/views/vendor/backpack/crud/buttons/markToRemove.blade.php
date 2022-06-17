@if ($crud->hasAccess('marktoremove'))
  @if ($entry->is_marked_to_remove)
    <input type="button" value="Unmark to Remove" onclick="markOrUnmarkToRemove(this)" data-id="{{ $entry->getKey() }}" data-mark-route="{{ url($crud->route.'/'.$entry->getKey().'/marktoremove') }}" data-unmark-route="{{ url($crud->route.'/'.$entry->getKey().'/unmarktoremove') }}" class="btn btn-warning" data-button-type="markorunmarktoremove">
  @else
    <input type="button" value="Mark to Remove"   onclick="markOrUnmarkToRemove(this)" data-id="{{ $entry->getKey() }}" data-mark-route="{{ url($crud->route.'/'.$entry->getKey().'/marktoremove') }}" data-unmark-route="{{ url($crud->route.'/'.$entry->getKey().'/unmarktoremove') }}" class="btn btn-primary" data-button-type="markorunmarktoremove">
  @endif  
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>
    if (typeof markOrUnmarkToRemove != 'function') {
      $("[data-button-type = markorunmarktoremove]").unbind('click');

      function markOrUnmarkToRemove(button) {
          // ask for confirmation before deleting an item
          // e.preventDefault();
          var button = $(button);
          var markRoute = button.attr('data-mark-route');
          var unmarkRoute = button.attr('data-unmark-route');

          var route;
          var successMessage;
          var failedMessage;

          // hardcode column number of flag showed in CRUD panel
          var columnNumber = 11;
          var fileId = button.attr('data-id');
          var rowNumber;
          var flagLabel;


          // assign different values depending on which button is clicked
          if (button.attr('value') == "Mark to Remove") {
              route = markRoute;
              successMessage = "<strong>Entry has been marked to remove successfully</strong>";
              failedMessage = "<strong>Failed to mark entry to remove</strong>";
              flagLabel = 'Sí';
              button.attr('value', "Unmark to Remove");
              button.attr('class', "btn btn-warning");
          } else if (button.attr('value') == "Unmark to Remove") {
              route = unmarkRoute;
              successMessage = "<strong>Entry has been unmarked to remove successfully</strong>";
              failedMessage = "<strong>Failed to unmark entry to remove</strong>";
              flagLabel = 'No';
              button.attr('value', "Mark to Remove");
              button.attr('class', "btn btn-primary");
          }


          $.ajax({
              url: route,
              type: 'POST',
              success: function(result) {
                  // Show an alert with the result
                  new Noty({
                    type: "success",
                    text: successMessage
                  }).show();

                  // Hide the modal, if any
                  $('.modal').modal('hide');

                  if (typeof crud !== 'undefined') {
                      // convert File ID column data to an array, use data Id to find row number
                      var fileIdArray = crud.table.column( 0 ).data().toArray();

                      for (var i = 0; i < fileIdArray.length; i++) {
                          if (fileIdArray[i].indexOf(fileId) != -1) {
                              rowNumber = i;
                              break;
                          }
                      }

                      // update content of a particular cell
                      crud.table.cell( rowNumber, columnNumber ).data(flagLabel);
                  }
              },
              error: function(result) {
                  // Show an alert with the result
                  new Noty({
                    type: "warning",
                    text: failedMessage
                  }).show();
              }
          });
      }
    }

    // make it so that the function above is run after each DataTable draw event
    // crud.addFunctionToDataTablesDrawEventQueue('cloneEntry');
</script>
@if (!request()->ajax()) @endpush @endif