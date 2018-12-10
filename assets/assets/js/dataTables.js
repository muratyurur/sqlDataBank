$(document).ready(function() {
    $('#datatable-responsive').DataTable({
        "language": {
            "url": "https://sql.muratyurur.com/assets/assets/js/dtTurkish.json",
        },
        "responsive": true,
        "bsort": false,
        "paging": false
    });
} );