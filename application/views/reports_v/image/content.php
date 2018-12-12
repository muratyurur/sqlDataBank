<div class="row">
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form data-url="<?php echo base_url("reports/refresh_file_list/$item->id"); ?>"
                      action="<?php echo base_url("reports/file_upload/$item->id"); ?>"
                      id="dropzone"
                      class="dropzone"
                      data-plugin="dropzone"
                      data-options="{ url: '<?php echo base_url("reports/file_upload/$item->id"); ?>'}">
                    <div class="dz-message">
                        <h3 class="m-h-lg">Yüklemek istediğiniz dosyaları buraya sürükleyip bırakabilirsiniz</h3>
                        <p class="m-b-lg text-muted">(Yüklemek için dosyalarınızı sürükleyip bırakabilir ya da buraya
                            tıklayabilirsiniz)</p>
                    </div>
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-9">
            <h4 class="m-b-lg">
                <b><?php echo $item->title; ?></b> raporuna ait dosyalar
            </h4>
        </div>
        <div class="col-md-3 content-container" style="text-align: right">
            <a href="<?php echo base_url("reports") ?>">
                <button class="btn btn-sm btn-outline btn-inverse ml-3">
                    <i class="fa fa-chevron-left"></i> Geri Dön
                </button>
            </a>
                <button data-url="<?php echo base_url("reports/fileDeleteAll/$item->id"); ?>"
                        type="button"
                        class="btn btn-sm btn-deepOrange btn-outline remove-btn">
                    <i class="fa fa-trash"></i> Tümünü Sil
                </button>
        </div><!-- END column -->
        <div class="col-md-12">
            <div class="widget">
                <div class="widget-body image_list_container">

                    <?php $this->load->view("{$viewFolder}/{$subViewFolder}/render_elements/file_list_v"); ?>

                </div><!-- .widget-body -->
            </div><!-- .widget -->
        </div><!-- END column -->
    </div>

