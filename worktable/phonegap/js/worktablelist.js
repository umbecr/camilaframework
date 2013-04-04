function init() {
alert('ciao');
//document.addEventListener("deviceready", onDeviceReady, true);
//if (!window.device)
//    onDeviceReady();
delete init;
}

function onDeviceReady() {
alert('ready');
	$('#busy').hide();

						var allWorktables = Worktable.all();

                         allWorktables.list(null, function (results) {
                             results.forEach(function (r) {
							 
				alert('sss');
							 
							 
		//$('#reportList').append('<li><a href="employeedetails.html?id=' + r.name + '">' + '<img src="pics/' + r.name + '" class="list-icon"/>' + '<p class="line1">' + r.name + ' ' + r.name + '</p>' +'<p class="line2">' + r.name + '</p>' + '<span class="bubble">0</span></a></li>');


                             });
                        });


    //db = window.openDatabase("EmployeeDirectoryDB", "1.0", "PhoneGap Demo", 200000);
    //db.transaction(getReportList, transaction_error);
}