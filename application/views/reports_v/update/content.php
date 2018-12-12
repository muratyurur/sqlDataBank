<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            <b><?php echo $item->title ?></b> adlı raporu düzenliyorsunuz...
        </h4>
    </div>
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("reports/update/$item->id/$item->gallery_type/$item->folder_name"); ?>" method="post">
                    <div class="form-group">
                        <label>Rapor Adı</label>
                        <input
                                name="title"
                                type="text"
                                class="form-control"
                                placeholder="Rapor adını giriniz..."
                                value="<?php echo $item->title; ?>">
                        <?php if (isset($form_error)) { ?>
                            <small class="input-form-error pull-right"> <?php echo form_error("title"); ?></small>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label>Müdürlük</label>
                        <div>
                            <select class="form-control" data-plugin="select2" name="department_id">
                                <?php foreach ($departments as $department) { ?>
                                    <option <?php echo ($item->department_id == $department->id) ? "selected" : "" ; ?>
                                            value="<?php echo $department->id; ?>">
                                        <?php echo $department->title ; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-md btn-outline"><i class="fa fa-refresh"></i>
                        Güncelle
                    </button>
                    <a href="<?php echo base_url("reports"); ?>">
                        <button type="button" class="btn btn-danger btn-md btn-outline"><i class="fa fa-ban"></i>
                            Vazgeç
                        </button>
                    </a>
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>