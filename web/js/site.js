$(function() {
    $("#treeID").on('treeview.change', function(event, key, name) {
        $(location).attr('href', '/web/index.php?r=post%2Findex&id=' + $("#treeID").val())
    });
});

function loadQuickSearchResult(controller, id) {
        if(controller == 'category'){
            $(location).attr('href', '/web/index.php?r=post%2Findex&id=' + id);
            return ;
        }

        $(location).attr('href', '/web/index.php?r=post%2Fview&id=' + id);
        return;

}


function favorites(ref, post_id) {
    if(ref.hasClass('fa-star-o'))
        action = 'add-favorites';
    else
        action = 'remove-favorites';

    $.get( "/web/index.php?r=profile%2F"+action, {'post_id': post_id}, function(result) {
        if(result){
            if(action == 'add-favorites'){
                ref.removeClass('fa-star-o');
                ref.addClass('fa-star');
            }else{
                ref.removeClass('fa-star');
                ref.addClass('fa-star-o');
            }
        }else{
            alert("Добавить в избранное не удалось.")
        }
    });
}