<?php if($crud->hasAccess('delete')): ?>
	<a href="javascript:void(0)" onclick="deployEntry(this)" data-route="<?php echo e(url($crud->route.'/'.$entry->getKey().'/deploytokobo')); ?>" class="btn btn-sm btn-info" data-button-type="delete"><i class="la la-trash"></i> <?php if($entry->kobo_id): ?> (Re) <?php endif; ?> Deploy to Kobo</a>
<?php endif; ?>




<?php $__env->startPush('after_scripts'); ?> <?php if(request()->ajax()): ?> <?php $__env->stopPush(); ?> <?php endif; ?>
<script>

	if (typeof deployEntry != 'function') {
	    $("[data-button-type=delete]").unbind('click');

	    function deployEntry(button) {
		    // ask for confirmation before deleting an item
		    // e.preventDefault();
            var button = $(button);
            var route = button.attr('data-route');
            var row = $("#crudTable a[data-route='"+route+"']").closest('tr');

            swal({
                title: "Are you sure?",
                text: "This will deploy the current version of the XLS File to Kobotools. The form will be shared with all admin users to allow collaborative testing.",
                icon: "info",
                buttons: {
                    cancel: {
                        text: "<?php echo trans('backpack::crud.cancel'); ?>",
                        value: null,
                        visible: true,
                        className: "bg-secondary",
                        closeModal: true,
	        		},
    		      	delete: {
                        text: "Yes - Deploy form to Kobotoolbox",
                        value: true,
                        visible: true,
                        className: "bg-success",
	        		}
		        },
            }).then((value) => {
                if (value) {
                    $.ajax({
                        url: route,
                        type: 'POST',
                        success: function(result) {
                            console.log(result);
                            new Noty({
                                type: "info",
                                text: "Deployment started"
                            }).show();
                        },
                        error: function(result) {
                            // Show an alert with the result
                            swal({
                                title: "Error",
                                text: "Something went wrong with deployment - please try again or contact the site admin",
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
	// crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
</script>
<?php if(!request()->ajax()): ?> <?php $__env->stopPush(); ?> <?php endif; ?>
<?php /**PATH /home/forge/weatherstations.stats4sd.org/resources/views/vendor/backpack/crud/buttons/deploy.blade.php ENDPATH**/ ?>