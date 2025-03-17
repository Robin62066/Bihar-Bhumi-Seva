async function api_call(m, ob) {
    let url = apiUrl + '?m=' + m;
    let result = await axios.post(url, ob);
    let resp = result.data;
    return resp;
}

$(document).ready(function () {
    $('form').submit(function () {
        $(this).find('.btn-submit').html("Please wait...").prop('disabled', 'disabled');
    })

    $('a.delete, .btn-delete').click(function () {
        if (!confirm('Are you sure to delete?'))
            return false;
    });

    $('.btn-confirm').click(function () {
        var msg = $(this).data('msg');
        if (!confirm(msg))
            return false;
    });

    $(document).on("input", ".numeric", function () {
        this.value = this.value.replace(/\D/g, '');
    });
})
