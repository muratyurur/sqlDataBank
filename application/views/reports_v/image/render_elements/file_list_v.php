<?php if (empty($items)) { ?>
    <div class="alert alert-warning text-center" style="padding: 8px; margin-bottom: 0px; s">
        <p style="font-size: larger">Henüz bu rapor için bir dosya yüklenmemiş...</p>
    </div>
<?php } else { ?>
    <table id="datatable-responsive" class="table table-bordered table-hover table-striped content-container">
        <thead>
        <th class="w20"><i class="fa fa-reorder"></i></th>
        <th class="w50">#id</th>
        <th class="w50">Dosya Türü</th>
        <th>Dosya Yolu / Adı</th>
        <th class="w50">Durumu</th>
        <th class="w150">İşlem</th>
        </thead>
        <tbody>
        <?php foreach ($items as $item) { ?>
            <tr id="ord-<?php echo $item->id; ?>">
                <td class="text-center"><i class="fa fa-reorder"></i></td>
                <td class="text-center"><?php echo $item->id; ?></td>
                <td class="text-center">
                    <img src="<?php echo base_url("assets/assets/images/file_types/$item->file_type") . ".png"; ?>"
                         style="height: 50px;"
                         alt="<?php echo $item->url; ?>">
                </td>
                <td><?php echo $item->url; ?></td>
                <td class="text-center">
                    <input
                        data-url="<?php echo base_url("reports/fileIsActiveSetter/$item->id"); ?>"
                        class="isActive"
                        type="checkbox"
                        data-switchery
                        data-color="#188ae2"
                        <?php echo ($item->isActive) ? "checked" : "" ?>
                    />
                </td>
                <td class="text-center">
                    <button
                            data-url="<?php echo base_url("reports/fileDelete/$item->id/$item->report_id"); ?>"
                            type="button"
                            class="btn btn-danger btn-sm btn-outline remove-btn"
                    >
                        <i class="fa fa-trash-o"></i>
                        Sil
                    </button>
                    <button
                            type="button"
                            class="btn btn-primary btn-sm btn-outline remove-btn"
                    >
                        <i class="fa fa-download"></i>
                        İndir
                    </button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>