<script src="<?php echo base_url("assets"); ?>/assets/js/dataTables.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<script src="<?php echo base_url("assets"); ?>/assets/js/clipboard.min.js"></script>

<script>
    var btns = document.querySelectorAll('button');
    var clipboard = new ClipboardJS(btns);
    clipboard.on('success', function(e) {
        console.log(e);
    });
    clipboard.on('error', function(e) {
        console.log(e);
    });
</script>
