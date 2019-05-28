<div class="modal fade" tabindex="1" role="dialog" id="plugin_generate_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" style="font-size: 16px; font-weight: 700;">GENERATE A PLUGIN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#!" method="post" id="generation_form" name="generation_form" onsubmit="return false;">
                    <div class="row">
                        <div class="col-5">
                            <div class="form-group">
                                <label for="" class="font-weight-bold text-secondary">Plugin Name</label>
                                <input type="text" name="pg_name" placeholder="Enter plugin name" class="form-control">
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <label for="" class="font-weight-bold text-secondary">Label Name</label>
                                <input type="text" name="pg_lb_name" placeholder="Name seen by user" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="" class="font-weight-bold text-secondary">Author Name</label>
                                <input type="text" name="pg_author" placeholder="Who is that hero ?" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="" class="font-weight-bold text-secondary">Licence</label>
                                <input type="text" name="pg_licence" placeholder="Plugin user rights" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="" class="font-weight-bold text-secondary">Description</label>
                                <textarea rows="3" name="pg_description" placeholder="Describe what your plugin does." class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <?= Poki\Posts::getCSRF() ?>
                            <button type="button" class="btn btn-default" onclick="document.forms.generation_form.reset()">RESET</button>
                            <button type="submit" class="btn btn-primary">GENERATE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>