var ind = 0;
var boxes = new Array();
var active = 1;

$(function () {
    var url = "custom_monitor/getDestination.php";
    
    $('.boxDestination').find('div').each(function() {
        var id = $(this).attr('id');
        if(id !== undefined){
            close($(this));
            boxes[ind] = $(this).children('p').children('b').text();
            ind++;
        }
    });

    $('.addTracking').click(function (e) {
        e.preventDefault();
        if (active == 1) {
            active = 0;            
            var tracking = $('.groupTracking').val();
            var index = getInd(tracking);
            var verify = verifySelected($('.boxDestination'), "group_" + index);
            boxes[index] = tracking;
            if (tracking != "empty" && !verify) {
                $.ajax({
                    url: url,
                    dataType: 'html',
                    type: 'post',
                    data: {
                        tracking: tracking,
                        ind: ind
                    },
                    beforeSend: function () {
                    },
                    success: function (data, textStatus) {
                        console.log(data);
                        $('.boxDestination').append(data);
                        close($('#group_' + index));
                        ind++;
                        active = 1;
                    },
                    error: function (xhr, er) {
                        window.location.reload();
                    }
                });
            }else{
                active = 1;
            }
        }
    });
});
function verifySelected(obj, optionVal) {
    var status = false;
    obj.find("div").each(function () {
        if ($(this).attr('id') == optionVal) {
            status = true;
        }
    });
    return status;
}

function getInd(tracking) {
    var status = ind;
    $.each(boxes, function (index, value) {
        if (value == tracking) {
            status = index;
        }
    });
    return status;
}

function close(obj) {
    obj.find('.closeTracking').click(function (e) {
        e.preventDefault();
        obj.remove();
        ind--;
    });
}