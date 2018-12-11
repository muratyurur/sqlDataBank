<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            SQL Sorguları
            <a class="btn btn-outline btn-info btn-sm pull-right"
               href="<?php echo base_url("queries/new_form"); ?>">
                <i class="fa fa-plus"></i> Yeni SQL Ekle
            </a>
        </h4>
    </div>
    <div class="col-md-12">
        <div class="widget p-lg">
            <?php if (empty($items)) { ?>
                <div class="alert alert-warning text-center" style="padding: 8px; margin-bottom: 0px; s">
                    <p style="font-size: larger">Henüz hiçbir SQL Sorgusu eklenmemiş. Eklemek için
                        <a href="<?php echo base_url("queries/new_form"); ?>">
                            tıklayın
                        </a>...
                    </p>
                </div>
            <?php } else { ?>
                <table id="datatable-responsive"
                       class="table table-striped table-hover table-bordered content-container">
                    <thead>
                    <th class="w50">#id</th>
                    <th class="w200">Müdürlük Adı</th>
                    <th>Açıklama</th>
                    <th class="w75">Kaydeden</th>
                    <th class="w150">Kayıt Tarihi</th>
                    <th class="w75">Güncelleyen</th>
                    <th class="w150">Güncelleme Tarihi</th>
                    <th class="w50">Durumu</th>
                    <th class="w250">İşlem</th>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item) { ?>
                        <tr>
                            <td class="text-center"><?php echo $item->id; ?></td>
                            <td><?php echo get_departmentName($item->department_id); ?></td>
                            <td><?php echo $item->description; ?></td>
                            <td class="text-center"><?php echo get_username($item->createdBy); ?></td>
                            <td class="text-center"><?php echo get_readable_date($item->createdAt); ?></td>
                            <td class="text-center"><?php echo get_username($item->updatedBy); ?></td>
                            <td class="text-center"><?php echo ($item->updatedAt == "" ? "" : get_readable_date($item->updatedAt)); ?></td>
                            <td class="text-center">
                                <input
                                        data-url="<?php echo base_url("queries/isActiveSetter/$item->id"); ?>"
                                        class="isActive"
                                        type="checkbox"
                                        data-switchery
                                        data-color="#188ae2"
                                    <?php echo ($item->isActive) ? "checked" : "" ?>
                                />
                            </td>
                            <td class="text-center">
                                <button
                                        data-url="<?php echo base_url("queries/delete/$item->id"); ?>"
                                        type="button"
                                        class="btn btn-danger btn-sm btn-outline remove-btn"
                                >
                                    <i class="fa fa-trash-o"></i>
                                    Sil
                                </button>
                                <a href="<?php echo base_url("queries/update_form/$item->id"); ?>">
                                    <button type="button" class="btn btn-primary btn-sm btn-outline">
                                        <i class="fa fa-pencil-square-o"></i>
                                        Düzenle
                                    </button>
                                </a>
                                <a href="#">
                                    <button type="button" class="btn btn-purple btn-sm btn-outline" data-toggle="modal" data-target="#<?php echo $item->id; ?>">
                                        <i class="fa fa-search"></i>
                                        Görüntüle
                                    </button>
                                </a>
                            </td>
                        </tr>
                        <div class="modal fade" id="<?php echo $item->id; ?>">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"><?php echo get_departmentName($item->department_id); ?></h4>
                                        <p><?php echo $item->description; ?></p>
                                        <button
                                                type="button"
                                                class="btn btn-success btn-sm pull-right"
                                                data-clipboard-target="#query-<?php echo $item->id; ?>"
                                                data-dismiss="modal">
                                            <i class="fa fa-copy"></i>
                                             Kopyala
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <pre><code class="sql hljs" id="query-<?php echo $item->id; ?>"><?php echo $item->query; ?></code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div><!-- .widget -->
    </div><!-- END column -->
</div>

