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
    var template = `<div class="col small-gutter-col"><div class="card h-100 hover-border-primary border-0">`
        template += `<a href="groups/` + group.id + `">`
        template += `<div class="card-body text-center d-flex flex-column">`
        template += `<h5 class="text-primary font-weight-bold">` + group.name +`</h5>`
        template += `<small class="text-muted">` + group.headquarters.name + `</small>`
        template += `<small class="text-muted">` + group.study_time.name + `</small>`
        template += `<small class="text-muted">` + group.study_year.name + `</small>`
        template += `<small class="btn-icon-start text-muted">`
        if (group.teacher) {
            template += `<i class="icon icon-15 bi-award text-muted"></i> `
            template += `<span>` + group.teacher.names + ` ` + group.teacher.last_names + `</span>`
        } else {
            template += `<span>&nbsp;</span>`
        }
        if (group.specialty){
            template += `<span class="badge text-primary icon-12 me-2 position-absolute e-n2 t-2 z-index-1"><i class="icon bi-star-fill"></i></span>`
        }
        template += `</small><small class="mt-2 text-muted">` + group.student_quantity + ` estudiantes</small>`
        template += `</div></a></div></div>`
    return template;
}

jQuery('#select2Headquarters').change(function () { groups_filter(); });
jQuery('#select2StudyTime').change(function () { groups_filter(); });
jQuery('#select2StudyYear').change(function () { groups_filter(); });
jQuery('#searchName').keyup(function () { groups_filter(); });
