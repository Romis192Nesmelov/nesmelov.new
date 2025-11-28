$(document).ready(function () {
    // Print click
    $('#print a').click(function (e) {
        e.preventDefault();
        $(this).remove();
        window.print();
    });
});
