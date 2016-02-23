$(function(){

    var primaryColor = "#ff6d6d",
        baseColor = "#333";

    //*/////////////////////////////////////////////////
    // form submission
    //*/////////////////////////////////////////////////

    var $form = $('form[name="url-input"]');

    $form.on('submit', function(){
        $('input[type="submit"], div.message').addClass("submitting");
    });

    //*/////////////////////////////////////////////////
    // form logic
    //*/////////////////////////////////////////////////

    $form.click(function(){
        if ($('#custom-dimensions').is(":checked")) {
            $('fieldset.custom-dimensions').slideDown().show();
        } else {
            $('fieldset.custom-dimensions').slideUp();
        }

        $('label').css('color', baseColor);
        $('label:has(input[type="radio"]:checked)').css('color', primaryColor);

        if ($('#auth').is(":checked")) {
            $('fieldset.auth').slideDown().show();
        } else {
            $('fieldset.auth').slideUp();
        }

    });

    //*/////////////////////////////////////////////////
    // gathering download
    //*/////////////////////////////////////////////////

    var downloadCheck = false;

    $('#download').click(function(){
        downloadCheck = true;
    });

    var confirmExit = function(){
        if ((window.location.pathname === "/grabShots") && (downloadCheck === false)) {
            return "You've attempted to leave this page without retrieving your download.";
        }
    };

    window.onbeforeunload = confirmExit;
    console.log(window.location.pathname );
});
