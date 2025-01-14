var spotLoop;
var completionLoop;

function getStatus(){
    var status = 0;
    $.ajax({
        method:"POST",
        url:"../getstatus.php",
        datatype:"json",
        data:{isOnReqPage: 0},
        async: false,
        success:function(statusCode){
            console.log(statusCode);
            console.log(typeof statusCode);
            // if (statusCode != 2)
            // {
            //     clearInterval(spotCheck);
            // }
            status = statusCode;
        }
    });
    return status;
};

function submitOffer() {
    var select = document.getElementById("parking_lot");
    var parkingLot = select.value;
    offerParkingSpot(parkingLot);
};

function offerParkingSpot(parkingLot){
    // var lotStr = JSON.stringify(lot);
    // console.log(lot)
    // console.log(typeof lot);
    var status = document.getElementById('offer_status');
    status.innerHTML = "";

    $.ajax({
        method: "POST",
        url: "manage.php",
        data: 
        {
            action:"add",
            parkingLot:parkingLot
        },
        dataType: "html",
        success:function(data){
            $("#offer_status").html(data);
            spotCheck();
        }
    });
};

function cancelOffer(){
    var status = document.getElementById('offer_status');
    status.innerHTML = "";

    $.ajax({
        method: "POST",
        url: "manage.php",
        data: 
        {
            action:"cancel",
        },
        dataType: "html",
        success:function(data){
            $("#offer_status").html(data)
        }
    });
};

function spotCheck(){
    var statusCode = getStatus();

    spotLoop = setInterval(function(){
        console.log(statusCode);
        if (statusCode == 20){
            clearInterval(spotLoop);
            checkCompletion();
            console.log("first check");
        } else if (statusCode == 2){
            statusCode = getStatus();
            $.ajax({
                method:"POST",
                url:"offerstatus.php",
                datatype:"html",
                success:function(data){
                    $("#offer_status").html(data);
                }
            });
        } else {
            output = "<div class = 'alert alert-info'><strong>You are not yet offering your parking spot. </strong><br>Please enter your parking lot to continue or you can click <strong>Cancel</strong> if you would like to cancel your offer.</div>";
            $("#offer_status").html(output);
        }

        if (statusCode == 20){
            clearInterval(spotLoop);
            checkCompletion();
            console.log("second check");

        }
    }, 5000);
    if (statusCode == 20){
        clearInterval(spotLoop);
        checkCompletion();
        console.log("third check");
    }
};
function offerDetails(){
    location.href = "details.php";
};

function updateOffer(){
    var status = document.getElementById('offer_status');
    status.innerHTML = "";

    $.ajax({
        method: "POST",
        url: "manage.php",
        data: 
        {
            action:"update",
        },
        dataType: "html",
        success:function(data){
            $("#offer_status").html(data)
            checkCompletion();
        }
    });

};

function checkCompletion(){
    var status = getStatus();

    var compStatus = document.getElementById('comp_status');
    compStatus.innerHTML = "";

    completionLoop = setInterval(function(){
        status = getStatus();

        compStatus.innerHTML = "";

        $.ajax({
            method:"POST",
            url:"checkcompletion.php",
            datatype:"html",
            success:function(data){
                if(status == 0){
                    clearInterval(completionLoop);
                    $("#comp_status").html(data);
                } else if (status == 2){
                    clearInterval(completionLoop);
                    $("#offer_status").html(data);
                    spotCheck();
                } else{
                    $("#comp_status").html(data);
                }
            }
        });

        if(status == 0){
            clearInterval(completionLoop);
        }

    }, 5000);
};

// document.getElementById("myButton").onclick = function () {
//     location.href = "www.yoursite.com";
// };