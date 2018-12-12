<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            <b><?php echo get_departmentName($item->deparment_id); ?></b> müdürlüğüne ait bir sorguyu düzenliyorsunuz...
        </h4>
    </div>
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("queries/update/$item->id"); ?>" method="post">
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

                    <div class="form-group">
                        <label>Açıklama</label>
                        <textarea name="description" class="form-control"><?php echo $item->description; ?></textarea>
                        <?php if (isset($form_error)) { ?>
                            <small class="input-form-error pull-right"> <?php echo form_error("description"); ?></small>
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label>SQL</label>
                        <textarea rows="25" name="query" class="form-control"><?php echo $item->query; ?></textarea>
                        <?php if (isset($form_error)) { ?>
                            <small class="input-form-error pull-right"> <?php echo form_error("query"); ?></small>
                        <?php } ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-md btn-outline"><i class="fa fa-refresh"></i>
                        Güncelle
                    </button>
                    <a href="<?php echo base_url("queries"); ?>">
                        <button type="button" class="btn btn-danger btn-md btn-outline"><i class="fa fa-ban"></i>
                            Vazgeç
                        </button>
                    </a>
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>