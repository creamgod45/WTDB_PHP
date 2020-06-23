var flag_debuger = 1;
$(function () {
    $('.toast').toast('show');
    var len = 19; // 超過50個字以"..."取代
    $(".JQellipsis").each(function (i) {
        if ($(this).text().length > len) {
            $(this).attr("title", $(this).text());
            var text = $(this).text().substring(0, len - 1) + "...";
            $(this).text(text);
        }
    });
    $('#tofooter').click(function () {
        var taget = document.getElementById("chatroom").scrollHeight;
        $('#chatroom').animate({
            scrollTop: taget - 500
        }, 1000 + (taget / 2), 'easeOutCubic', function () {
            $('#tofooter').attr('disabled', 'disabled');
        });
    });
    $("#debuger").hide();
    $("#debuger_background").css("height","auto");
    
    $("#debuger_btn").click(function(){
        if(flag_debuger==0){
            $("#debuger").fadeOut(500);
            setTimeout(() => {
                $("#debuger_background").css("height","auto");
            }, 600);
            flag_debuger=1;
        }else{
            $("#debuger").fadeIn(500);
            setTimeout(() => {
                $("#debuger_background").css("height","500px");
            }, 600);
            flag_debuger=0;
        }
    });
    console.log(flag_debuger);
});


function deleteall(){
    if(document.getElementById("select_rows").value === ""){
        return false;
    }
}

function readFile(obj) {
    var file = obj.files[0];
    //判斷型別是不是圖片 

    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function (e) {
        dealImage(this.result, {
            width: 144,
            height: 144
        }, function (base) {
            $('#blah').attr('src', base);
            $('#images').attr('value', base);
        });
    }
}

/**
 * 圖片壓縮，預設同比例壓縮
 * @param {Object} path
 * pc端傳入的路徑可以為相對路徑，但是在移動端上必須傳入的路徑是照相圖片儲存的絕對路徑
 * @param {Object} obj
 * obj 物件 有 width， height， quality(0-1)
 * @param {Object} callback
 * 回撥函式有一個引數，base64的字串資料
 */
function dealImage(path, obj, callback) {
    var img = new Image();
    img.src = path;
    img.onload = function () {
        var that = this;
        // 預設按比例壓縮
        var w = that.width,
            h = that.height,
            scale = w / h;
        w = obj.width || w;
        h = obj.height || (w / scale);
        var quality = 0.7; // 預設圖片質量為0.7
        //生成canvas
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        // 建立屬性節點
        var anw = document.createAttribute("width");
        anw.nodeValue = w;
        var anh = document.createAttribute("height");
        anh.nodeValue = h;
        canvas.setAttributeNode(anw);
        canvas.setAttributeNode(anh);
        ctx.drawImage(that, 0, 0, w, h);
        // 影象質量
        if (obj.quality && obj.quality <= 1 && obj.quality > 0) {
            quality = obj.quality;
        }
        // quality值越小，所繪製出的影象越模糊
        var base64 = canvas.toDataURL('image/jpeg', quality);
        // 回撥函式返回base64的值
        callback(base64);
    }
}