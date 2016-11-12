
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length,c.length);
            }
        }
        return "";
    }

    function checkCookie() {
        var username=getCookie("username");
        if (username!="") {
            alert("Welcome again " + username);
        } else {
            username = prompt("Please enter your name:", "");
            if (username != "" && username != null) {
                setCookie("username", username, 365);
            }
        }
    }

    function empty( val ) {

        // test results
        //---------------
        // []        true, empty array
        // {}        true, empty object
        // null      true
        // undefined true
        // ""        true, empty string
        // ''        true, empty string
        // 0         false, number
        // true      false, boolean
        // false     false, boolean
        // Date      false
        // function  false

        if (val === undefined)
            return true;

        if (typeof (val) == 'function' || typeof (val) == 'number' || typeof (val) == 'boolean' || Object.prototype.toString.call(val) === '[object Date]')
            return false;

        if (val == null || val.length === 0)        // null or 0 length array
            return true;

        if (typeof (val) == "object") {
            // empty object

            var r = true;

            for (var f in val)
                r = false;

            return r;
        }

        return false;
    }