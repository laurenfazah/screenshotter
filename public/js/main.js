$(function(){

    //*/////////////////////////////////////////////////
    // form submission
    //*/////////////////////////////////////////////////

    var $form = $('form[name="url-input"]');

    $form.on('submit', function(){
        $('input[type="submit"], div.message').addClass("submitting");
    });

    $('input[name="height"]').val("5000");
    $('input[name="width"]').val("2000");

    //*/////////////////////////////////////////////////
    // form logic
    //*/////////////////////////////////////////////////

    $form.click(function(){
        if ($('#custom-dimensions').is(":checked")) {
            console.log("checked");
            $('div.custom-dimensions').slideDown().show();
        } else {
            $('div.custom-dimensions').slideUp();
        }
    });


});
