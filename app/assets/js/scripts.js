var chart;
var pusher = new Pusher('c1f79d5768d5b59431e5');
$(document).ready(function () {

    $('body').scrollspy({ target: '#docs-nav' });

    updateAttending();
    function updateAttending() {
        if ($('#current-attendants').length) {
            var courseCode = $("#current-attendants").data('coursecode');
            $("#current-attendants").html("");
            $.get("/api/checkins/" + courseCode, function (data) {
                $(data).each(function (i, t) {
                    $("#current-attendants").append("<li class='list-group-item'>" + data[i]["username"] + "</li>");
                })
            });

        }
    }

    var channel = pusher.subscribe('checkins');

    channel.bind('checkins-updated',
        function(data) {
            flashUpdate();
            updateAttending();
        }
    );

    function flashUpdate(){
        playSound("notification");
        $('#updating-icon').css("visibility", "visible");
        setTimeout(function(){
            $('#updating-icon').css("visibility","hidden");
        },1400);
    }

    function playSound(filename){
        $('body').append('<div id="sound"></div>');
        document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="/app/assets/sounds/' + filename + '.mp3" type="audio/mpeg" /><source src="' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3" /></audio>';
    }
});