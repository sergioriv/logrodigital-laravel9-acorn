/* Page: logro.group.index */

function groups_filter()
{
    $.get("groups.filter",
    {
        headquarters:   jQuery('#select2Headquarters').val(),
        studyTime:      jQuery('#select2StudyTime').val(),
        studyYear:      jQuery('#select2StudyYear').val(),
        name:           jQuery('#searchName').val()
    }, function(data){
        var groups = '';
        data.forEach(group => {
            groups += template_card_group( group );
        });

        jQuery('#groupsList').html(groups)
    });
}

function template_card_group(group)
{
    var template = `<div class="col small-gutter-col"><div class="card h-100">`
        template += `<div class="card-body text-center d-flex flex-column">`
        template += `<h5 class="text-primary font-weight-bold">` + group.name +`</h5>`
        template += `<span>` + group.headquarters.name + `</span>`
        template += `<span>` + group.study_time.name + `</span>`
        template += `<span>` + group.study_year.name + `</span>`
        template += `<span class="btn-icon-start">`
        template += `<i class="icon icon-15 bi-award text-primary"></i> `
        template += `<span>` + group.teacher.first_name + ` ` + group.teacher.father_last_name + `</span>`
        template += `</span></div></div></div>`
    return template;
}

jQuery('#select2Headquarters').change(function () { groups_filter(); });
jQuery('#select2StudyTime').change(function () { groups_filter(); });
jQuery('#select2StudyYear').change(function () { groups_filter(); });
jQuery('#searchName').keyup(function () { groups_filter(); });
