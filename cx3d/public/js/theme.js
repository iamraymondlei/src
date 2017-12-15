var storage,fail,uid;

try{
//    uid = new Date;
    storage=window.localStorage;
//    storage.setItem(uid,uid);
//    fail = storage.getItem(uid)!==uid;
//    storage.removeItem(uid);
//    fail&&(storage=false);
}catch(e){}

if(storage){
    try{
        var usedSkin=localStorage.getItem('config-skin');
        if(usedSkin!=='' && usedSkin!==null){
            document.body.className=usedSkin;
        }
        else{
            document.body.className='theme-whbl';//theme-whbl
        }
    }
    catch(e){
        document.body.className='theme-whbl';//theme-whbl
    }
}
else{
    document.body.className='theme-whbl';//theme-whbl
    //theme-amethyst
    //theme-blue
    //theme-red
    //theme-turquoise
    //theme-whbl
    //theme-white
}

$(function ($) {
    var storage = window.localStorage;
    try {
//        uid = new Date;
//        (storage = window.localStorage).setItem(uid, uid);
//        fail = storage.getItem(uid) != uid;
//        storage.removeItem(uid);
//        fail && (storage = false);
    } catch (e) {}
        
    $('#skin-colors .skin-changer').on('click', function () {
        $('body').removeClassPrefix('theme-');
        $('body').addClass($(this).data('skin'));
        $('#skin-colors .skin-changer').removeClass('active');
        $(this).addClass('active');
        writeStorage(storage, 'config-skin', $(this).data('skin'));
    });
});

function writeStorage(storage, key, value) {
    if (storage) {
        try {
            localStorage.setItem(key, value);
        } catch (e) {
            console.log(e);
        }
    }
}

$.fn.removeClassPrefix = function (prefix) {
    this.each(function (i, el) {
        var classes = el.className.split(" ").filter(function (c) {
            return c.lastIndexOf(prefix, 0) !== 0;
        });
        el.className = classes.join(" ");
    });
    return this;
};