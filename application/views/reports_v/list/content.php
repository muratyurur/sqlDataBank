<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            Rapor Listesi
            <a class="btn btn-outline btn-info btn-sm pull-right"
               href="<?php echo base_url("reports/new_form"); ?>">
                <i class="fa fa-plus"></i> Yeni Rapor Ekle
            </a>
        </h4>
    </div>
    <div class="col-md-12">
        <div class="widget p-lg">
            <?php if (empty($items)) { ?>
                <div class="alert alert-warning text-center" style="padding: 8px; margin-bottom: 0px; s">
                    <p style="font-size: larger">Henüz hiçbir rapor eklenmemiş. Eklemek için
                        <a href="<?php echo base_url("reports/new_form"); ?>">
                            tıklayın
                        </a>...
                    </p>
                </div>
            <?php } else { ?>
                <table id="datatable-responsive"
                       class="table table-striped table-hover table-bordered content-container">
                    <thead>
                    <th class="w20"><i class="fa fa-reorder"></i></th>
                    <th class="w50">#id</th>
                    <th>Müdürlük</th>
                    <th>Rapor Adı</th>
                    <th>url</th>
                    <th class="w75">Durumu</th>
                    <th class="w300">İşlem</th>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item) { ?>
                        <tr id="ord-<?php echo $item->id; ?>">
                            <td class="text-center"><i class="fa fa-reorder"></i></td>
                            <td class="text-center"><?php echo $item->id; ?></td>
                            <td class="text-center"><?php echo get_departmentName($item->department_id); ?></td>
                            <td class="text-center"><?php echo $item->title; ?></td>
                            <td class="text-center"><?php echo $item->url; ?></td>
                            <td class="text-center">
                                <input
                                        data-url="<?php echo base_url("reports/isActiveSetter/$item->id"); ?>"
                                        class="isActive"
                                        type="checkbox"
                                        data-switchery
                                        data-color="#188ae2"
                                    <?php echo ($item->isActive) ? "checked" : "" ?>
                                />
                            </td>
                            <td class="text-center">
                                <button
                                        data-url="<?php echo base_url("reports/delete/$item->id"); ?>"
                                        type="button"
                                        class="btn btn-danger btn-sm btn-outline remove-btn"
                                >
                                    <i class="fa fa-trash-o"></i>
                                    Sil
                                </button>
                                <a href="<?php echo base_url("reports/update_form/$item->id"); ?>">
                                    <button type="button" class="btn btn-primary btn-sm btn-outline">
                                        <i class="fa fa-pencil-square-o"></i>
                                        Düzenle
                                    </button>
                                </a>
                                <a href="<?php echo base_url("reports/upload_form/$item->id"); ?>">
                                    <button type="button" class="btn btn-inverse btn-sm btn-outline">
                                        <i class="fa fa-folder-open-o"></i>
                                        Rapor Dosyaları
                                    </button>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div><!-- .widget -->
    </div><!-- END column -->
</div>