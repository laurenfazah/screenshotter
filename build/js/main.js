$(function(){

    var form = $('form[name="url-input"]'),
        inputField = $('input[name="url"]');

    form.on('submit', function(e){
        e.preventDefault();

        //get user input
        var inputUrl = inputField.val();


        // strip punctuation from url
        var folderName = extractDomain(inputUrl)+currentDate();
        console.log(folderName);

        // create folder on desktop with name


        // find all urls on page, save to array


        // screenshot each page


        // save pages to folder


    });


    var extractDomain = function(url) {
        var domain;
        //find & remove protocol (http, ftp, etc.) and get domain
        if (url.indexOf("://") > -1) {
            domain = url.split('/')[2];
        }
        else {
            domain = url.split('/')[0];
        }

        //find & remove port number
        domain = domain.split(':')[0];

        return domain;
    };

    var currentDate = function(){
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();

        if(dd<10) {
            dd='0'+dd;
        }

        if(mm<10) {
            mm='0'+mm;
        }

        today = '-'+mm+'_'+dd+'_'+yyyy;
        return today;
    };

});
