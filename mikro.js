var hash = "882c2071d8ce7ea8912929bbea384d2d2e653bf9";
var BASE_URL = 'http://localhost/neuanfang/mikro.php';


$(document).ready(function () {    
    check_status();                    
    setInterval('check_status()', 5000);
});
                   

function check_status() {    
    $.ajax({ 
        url: BASE_URL,
        data: {status: hash},
        type: 'post',
        success: function(output) {
            console.log(output);
            if (output == "Access denied") {
                alert("HTTP_401");
            } else if (output == 1) {
                var head = "Device is Unmuted";
                var link = '<form action="javascript:void(0)" method="post"><input onclick="new_mute(true)" value="Status Update" type="image" src="Icon_GREEN.png" /></form>';
            } else if (output == 0) {
                var head = "Device is Muted";
                var link = '<form action="javascript:void(0)" method="post"><input onclick="new_mute(false)" value="Status Update" type="image" src="Icon_RED.png" /></form>';
            }
            $("#HEADER").html(head);
            $("#SYMBOL").html(link);                                                  
        },
        complete: function() {
        }
    });
}

function new_mute(mute) {
    $.ajax({ 
        url: BASE_URL,
        data: mute == false ? { unmute: hash} : { mute: hash},
        type: 'post',
        success:function(output) {                                         
        }
    }).done(check_status);
}