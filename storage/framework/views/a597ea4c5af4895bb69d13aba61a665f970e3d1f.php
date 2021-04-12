<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-12 col-md-9 col-lg-6">
            <h3 class="text-center mb-4"><?php echo e(trans('backpack::base.reset_password')); ?></h3>
            <div class="nav-steps-wrapper">
                <ul class="nav nav-tabs">
                      <li class="nav-item"><a class="nav-link disabled text-muted"><strong><?php echo e(trans('backpack::base.step')); ?> 1.</strong> <?php echo e(trans('backpack::base.confirm_email')); ?></a></li>
                      <li class="nav-item active"><a class="nav-link active"><strong><?php echo e(trans('backpack::base.step')); ?> 2.</strong> <?php echo e(trans('backpack::base.choose_new_password')); ?></a></li>
                </ul>
            </div>
            <div class="nav-tabs-custom">
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>
                    <form class="col-md-12 p-t-10" role="form" method="POST" action="<?php echo e(route('backpack.auth.password.reset')); ?>">
                        <?php echo csrf_field(); ?>


                        <input type="hidden" name="token" value="<?php echo e($token); ?>">

                        <div class="form-group">
                            <label class="control-label" for="email"><?php echo e(trans('backpack::base.email_address')); ?></label>

                            <div>
                                <input type="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" id="email" value="<?php echo e($email ?? old('email')); ?>">

                                <?php if($errors->has('email')): ?>
                                    <span class="invalid-feedback">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="password"><?php echo e(trans('backpack::base.new_password')); ?></label>

                            <div>
                                <input type="password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" name="password" id="password">

                                <?php if($errors->has('password')): ?>
                                    <span class="invalid-feedback">
                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="password_confirmation"><?php echo e(trans('backpack::base.confirm_new_password')); ?></label>
                            <div>
                                <input type="password" class="form-control<?php echo e($errors->has('password_confirmation') ? ' is-invalid' : ''); ?>" name="password_confirmation" id="password_confirmation">

                                <?php if($errors->has('password_confirmation')): ?>
                                    <span class="invalid-feedback">
                                        <strong><?php echo e($errors->first('password_confirmation')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div>
                                <button type="submit" class="btn btn-block btn-primary">
                                    <?php echo e(trans('backpack::base.change_password')); ?>

                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(backpack_view('layouts.plain'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/forge/weatherstations.stats4sd.org/vendor/backpack/crud/src/resources/views/base/auth/passwords/reset.blade.php ENDPATH**/ ?>