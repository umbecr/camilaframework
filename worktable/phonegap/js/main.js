/**
 *
 *  Base64 encode / decode
 *  http://www.webtoolkit.info/
 *
 **/

var Base64 = {

    // private property
    _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    // public method for encoding
    encode : function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output +
            this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
            this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },

    // public method for decoding
    decode : function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    // private method for UTF-8 encoding
    _utf8_encode : function (string) {
        string = string.replace(/\r\n/g,"\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    // private method for UTF-8 decoding
    _utf8_decode : function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while ( i < utftext.length ) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}

function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function onDeviceReadyLogin() {
	$("#loginForm").on("submit",handleLogin);
}

function syncAllWorktables() {
	var allWorktables = Worktable.all().order("id", true);
	allWorktables.list(null, function (results) {
		results.forEach(function (r) {
			var id = Math.ceil(r.id);
			//	alert(Worktables[id]);
			Worktables[id].syncAll(persistence.sync.preferLocalConflictHandler, function() {
				alert('Done!');
			});
		});
	});
}


function syncWorktable(id) {
	id = Math.ceil(r.id);

	Worktables[id].syncAll(persistence.sync.preferLocalConflictHandler, function() {
		alert('Done!');
	});
}


function onDeviceReadyWorktableList() {
	defineWorktables();

	var allWorktables = Worktable.all().order("sequence", true);
	var categories = [];

    allWorktables.list(null, function (results) {
		results.forEach(function (r) {
			var	id = Math.ceil(r.id);

			if (r.category == '')
			{
				$('#itemList').append('<li><a rel="external" href="worktable_detail.html?id='+id+'">'+r.name+'</a></li>');
			}
			else
			{
				if ($.inArray(r.category,categories) < 0)
				{
					$('#itemList').append('<li><a rel="external" href="worktable_category_detail.html?id='+escape(r.category)+'">'+r.category+'</a></li>');
					categories[categories.length]=r.category;
				}
			}
			$("#itemList").listview("refresh");
        });
    });
}

function onDeviceReadyWorktableCategoryDetail() {
	defineWorktables();
	var id = unescape(getUrlVars()["id"]);
	$("#pageTitle").html(id);

	var allWorktables = Worktable.all().filter("category", '=', id).order("sequence", true);
    allWorktables.list(null, function (results) {
		results.forEach(function (r) {
			$('#itemList').append('<li><a rel="external" href="worktable_detail.html?id='+r.id+'">'+r.name+'</a></li>');
			$("#itemList").listview("refresh");
        });
    });

}

function onDeviceReadyWorktableDetail() {
	defineWorktables();
return;
	var id = unescape(getUrlVars()["id"]);
	$("#pageTitle").html(id);

	var w = Worktables[id].all();
    w.list(null, function (results) {
		results.forEach(function (r) {
			$('#itemList').append('<li><a href="worktable_category_detail.html?id='+escape(r.id)+'">'+r.id+'</a></li>');
			$("#itemList").listview("refresh");
        });
    });


}

function init() {
//document.addEventListener("deviceready", onDeviceReady, true);
//if (!window.device)
//    onDeviceReady();
delete init;
}

function checkPreAuth() {
    var form = $("#loginForm");
    if(window.localStorage["username"] != undefined && window.localStorage["password"] != undefined) {
        $("#username", form).val(window.localStorage["username"]);
        $("#password", form).val(window.localStorage["password"]);
        //handleLogin();
    }
}

function defineWorktables() {
	var allWorktables = Worktable.all().order("id", true);
	allWorktables.list(null, function (results) {
		results.forEach(function (r) {
			//alert(r.schema);
			//var schema = jQuery.parseJSON();
			var id = Math.ceil(r.id);
			eval("Worktables["+id+"] = persistence.define('Worktable"+id+"'," +r.schema+");");
			//alert(r.id);
			Worktables[id].enableSync('http://192.168.0.1:4001/worktable/rest/persistenceChanges/'+id);
		});
		
		persistence.schemaSync(function(tx) { 
		//		alert('synched');
		});

			var id = unescape(getUrlVars()["id"]);
	$("#pageTitle").html(id);

	var w = Worktables[id].all();
    w.list(null, function (results) {
		results.forEach(function (r) {
			$('#itemList').append('<li><a href="worktable_category_detail.html?id='+escape(r.id)+'">'+r.id+'</a></li>');
			$("#itemList").listview("refresh");
        });
    });

	
	});
}

function getWorktableSchema(url) {
  var strReturn = "";
  //return strReturn;

/*  var Task = persistence.define('Task', {
  name: "TEXT",
  description: "TEXT",
  done: "BOOL"
});*/

  jQuery.ajax({
    url: url,
    success: function(html) {
		strReturn = "{";
	  	var obj = jQuery.parseJSON(html);
		var recordCount = obj.camila_record_count;
		var table = obj.table;

		for (j=0; j<table.rows.length; j++)
		{
			var name = table.rows[j].c[7].v;
			//strReturn += table.rows[j].c[1].v;
			var type = table.rows[j].c[2].v;
			type = "TEXT";
			
			strReturn += name + ": \""+ type + "\"";
			if (j < (table.rows.length-1) )
			{
				strReturn += ",";
			}
		}
		
		strReturn += "}";
    },
    async:false,
    error: function (xhr, ajaxOptions, thrownError) {
	alert('KO :-(' + thrownError);
	}
  });

  return strReturn;
}

function handleLogin() {

	$('.ui-loader').css('display', 'block');

		var form = $("#loginForm");
    //disable the button so we can't resubmit while we wait
    $("#submitButton",form).attr("disabled","disabled");
	var s = $("#server", form).val();
    var u = $("#username", form).val();
    var p = $("#password", form).val();
	var url = s+"/rest/worktable";
    console.log("click");

    if(u != '' && p!= '' && s!='' && u != null && p!= null && s!=null) {
	
	$.ajax({
            type: "GET",
            url: url,
            /*dataType: "json",*/
            crossDomain: true,
            beforeSend: function(xhr){
                xhr.setRequestHeader("Authorization", "Basic " + Base64.encode(u + ":" + p));
            },
            success: function ( response ) {

			    //alert(response);
				var obj = jQuery.parseJSON(response);
				var recordCount = obj.camila_record_count;
				var table = obj.table;
				//for (i=0; i<table.cols.length; i++)
				//    alert(table.cols[i].label);
				persistence.reset();

				persistence.schemaSync(function(tx) { 
                    // tx is the transaction object of the transaction that was
                    // automatically started
					//alert('synched');
					for (i=0; i<table.rows.length; i++)
					{
                        var w = new Worktable();
						w.id = table.rows[i].c[0].v;
                        w.name = table.rows[i].c[2].v;
                        w.category = table.rows[i].c[4].v;
						w.sequence = table.rows[i].c[1].v;
						w.schema = getWorktableSchema(s+"/rest/worktablecolumn/"+table.rows[i].c[0].v);
                        persistence.add(w);
					}

					persistence.flush(function() {
						//alert('Done flushing');
						   
						$('.ui-loader').css('display', 'none');
						
						$.mobile.changePage("worktable_list.html", { reloadPage: true});
                    });

               });
				$("#submitButton").removeAttr("disabled");
            },
            error: function (xhr, ajaxOptions, thrownError) {
			            alert(ajaxOptions);
			alert(xhr.status);
						alert(xhr.responseText);
			console.log(xhr);
					$("#submitButton").removeAttr("disabled");

            }
        });


        /*$.post("http://www.coldfusionjedi.com/demos/2011/nov/10/service.cfc?method=login&returnformat=json", {username:u,password:p}, function(res) {
            if(res == true) {
                //store
                window.localStorage["username"] = u;
                window.localStorage["password"] = p;             
                $.mobile.changePage("some.html");
            } else {
                navigator.notification.alert("Your login failed", function() {});
            }
         $("#submitButton").removeAttr("disabled");
        },"json");*/
    } else {
	       navigator.notification.alert("You must enter a username and password", function() {});

        //Thanks Igor!
		        $("#submitButton").removeAttr("disabled");

    }
    return false;
}

