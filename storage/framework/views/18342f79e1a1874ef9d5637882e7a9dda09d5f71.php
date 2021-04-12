<?php if($crud->hasAccess('update')): ?>
	<a href="javascript:void(0)" onclick="syncData(this)" data-route="<?php echo e(url($crud->route.'/'.$entry->getKey().'/syncdata')); ?>" class="btn btn-sm btn-success" data-button-type="sync"><i class="la la-trash"></i> Get Data from Kobo</a>
<?php endif; ?>




<?php $__env->startPush('after_scripts'); ?> <?php if(request()->ajax()): ?> <?php $__env->stopPush(); ?> <?php endif; ?>
<script>

	if (typeof syncData != 'function') {
	    $("[data-button-type=sync]").unbind('click');

	    function syncData(button) {
		    // ask for confirmation before deleting an item
		    // e.preventDefault();
            var button = $(button);
            var route = button.attr('data-route');
            var row = $("#crudTable a[data-route='"+route+"']").closest('tr');

            $.ajax({
                url: route,
                type: 'POST',
                success: function(result) {
                    console.log(result);
                    new Noty({
                        type: "info",
                        text: "Sync Data Successful"
                    }).show();
                },
                error: function(result) {
                    // Show an alert with the result
                    swal({
                        title: "Error",
                        text: "Something went wrong while communicating with Kobotoolbox - please try again or contact the site admin",
                        icon: "error",
                        timer: 4000,
                        buttons: false,
                    });
                }
            });
		}
    }

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
</script>
<?php if(!request()->ajax()): ?> <?php $__env->stopPush(); ?> <?php endif; ?>
<?php /**PATH /home/forge/weatherstations.stats4sd.org/resources/views/vendor/backpack/crud/buttons/sync.blade.php ENDPATH**/ ?>