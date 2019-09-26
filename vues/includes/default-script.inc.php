<?php namespace Poki; ?>

<?php include INCLUDES . 'modal-add-category.inc.php' ?>
<!-- auto search route -->
<script>
    var baseroute       = '<?= Routes::find("base-route") ?>';
    var plugbaseroute   = <?= isset($plugin_base_url) ? "'" .$plugin_base_url. "'" : 'false'; ?>;
</script>
<!-- jQuery  -->
<script src="<?= THEME ?>assets/js/jquery.min.js"></script>
<script src="<?= THEME ?>assets/js/popper.min.js"></script>
<script src="<?= THEME ?>assets/js/bootstrap.min.js"></script>
<script src="<?= THEME ?>assets/js/modernizr.min.js"></script>
<script src="<?= THEME ?>assets/js/detect.js"></script>
<script src="<?= THEME ?>assets/js/fastclick.js"></script>
<script src="<?= THEME ?>assets/js/jquery.slimscroll.js"></script>
<script src="<?= THEME ?>assets/js/jquery.blockUI.js"></script>
<script src="<?= THEME ?>assets/js/waves.js"></script>
<script src="<?= THEME ?>assets/js/jquery.nicescroll.js"></script>
<script src="<?= THEME ?>assets/js/jquery.scrollTo.min.js"></script>
<script src="<?= THEME ?>assets/plugins/select2/select2.min.js"></script>
<script src="<?= THEME ?>assets/plugins/prism/prism.js"></script>

<!-- App js -->
<script src="<?= Files::script('fakeload.plugin') ?>"></script>
<script src="<?= Files::script('jquery.confirm.min') ?>"></script>
<script src="<?= Files::script('alerter.plugin') ?>"></script>
<script src="<?= Files::script('main') ?>"></script>
<script src="<?= THEME ?>assets/js/app.js"></script>