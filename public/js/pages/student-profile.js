jQuery("[logro='select2']").select2({
    minimumResultsForSearch: 30,
    placeholder: ''
});

$('#avatar').on("change", function() {
    $("#formAvatar").submit();
});

jQuery("[logro='studentDocument']").click(function() {
    jQuery('#modalStudentDocuments img').attr('src', $(this).data('image'));
});

jQuery("#selectStudentDocument").change(function () {
    var info = $(this).find('option:selected').attr('fileInfo');
    jQuery("#infoStudentDocument").removeClass('d-none').html(info);
});
