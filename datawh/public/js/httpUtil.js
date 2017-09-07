/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var HttpUtil = {
    HttpPost: function(url,args) {
        var form = $("<form method='post'></form>");
        form.attr({"action":url});
        for (arg in args)
        {
            var input = $("<input type='hidden'>");
            input.attr({"name":"mId"});
            input.val(args[arg]);
            form.append(input);
        }
        form.submit();
    }
};
