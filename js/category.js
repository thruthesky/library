$ = jQuery;
$(function(){
    var $body = $('body');
    $("body").on( "click",".category .button-wrapper .add", callback_category_add );
    $("body").on( "click",".category .button-wrapper .edit", callback_category_edit );
    $("body").on( "click",".category .cancel", callback_category_cancel );
});

function callback_category_add(){
    $this = $(this);
    var id = $this.attr("id");
    var form = renderAddForm( id );
    $(".category[category-id='" + id + "']").append( form );
    $(".category[category-id='" + id + "'] input[type='text']").focus();
    $this.remove();
}

function callback_category_edit(){
    $this = $(this);
    var id = $this.attr("id");
    var form = renderEditForm( id );
    $(".category[category-id='" + id + "'] .label .category-name").html( form );
    $(".category[category-id='" + id + "'] input[type='text']").select();
    $this.remove();
}



function category_delete( e ){
    return confirm( "Are you sure you want to delete - "+e+"?" );
}

function member_delete( e ){
    return confirm( "Are you sure you want to delete member - "+e+"?" );
}

function item_delete( e ){
    return confirm( "Are you sure you want to delete item - "+e+"?" );
}

function callback_category_cancel(){
    window.location.reload();
}

function renderEditForm( id ){
    if( $(".category[category-id='" + id + "'] .label .category-name a").length ){
        var text = $(".category[category-id='" + id + "'] .label .category-name a").text();
    }
    else{
        var text = $(".category[category-id='" + id + "'] .label .category-name").text();
    }
    var markup	=	"<form class='form-update' action='/library/category/admin/group/update'>" +
        "<fieldset><div class='row'><div class='value'><div class='element'>" +
        "<input type='hidden' name='id' value='" + id + "'>" +
        "<input type='text' name='name' value='" + text + "'><input type='submit' value='Update'>" +
        "</div></div></div></fieldset></form><span class='command cancel'>Cancel</span>";

    return markup;
}

function renderAddForm( id ){
    var markup	=	"<form class='form-update' action='/library/category/admin/group/add'>" +
        "<fieldset><div class='row'><div class='value'><div class='element'>" +
        "<input type='hidden' name='parent_id' value='" + id + "'>" +
        "<input type='text' name='name' value=''>" +
        "<input type='submit' value='Add'>" +
        "</div></div></div></fieldset></form><span class='command cancel'>Cancel</span>";

    return markup;
}