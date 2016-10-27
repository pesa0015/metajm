document.getElementById('set-opening-hours').onclick = function() {
	var isOpen = document.getElementsByClassName('is_open');
	var monday = {};
	monday.start = false;
	if (isOpen[0].checked) {
		monday.start = document.getElementById('start-monday').value;
		monday.end = document.getElementById('end-monday').value;
	}
	var tuesday = {};
	tuesday.start = false;
	if (isOpen[1].checked) {
		tuesday.start = document.getElementById('start-tuesday').value;
		tuesday.end = document.getElementById('end-tuesday').value;
	}
	var wednesday = {};
	wednesday.start = false;
	if (isOpen[2].checked) {
		wednesday.start = document.getElementById('start-wednesday').value;
		wednesday.end = document.getElementById('end-wednesday').value;
	}
	var thursday = {};
	thursday.start = false;
	if (isOpen[3].checked) {
		thursday.start = document.getElementById('start-thursday').value;
		thursday.end = document.getElementById('end-thursday').value;
	}
	var friday = {};
	friday.start = false;
	if (isOpen[4].checked) {
		friday.start = document.getElementById('start-friday').value;
		friday.end = document.getElementById('end-friday').value;
	}
	var saturday = {};
	saturday.start = false;
	if (isOpen[5].checked) {
		saturday.start = document.getElementById('start-saturday').value;
		saturday.end = document.getElementById('end-saturday').value;
	}
	var sunday = {};
	sunday.start = false;
	if (isOpen[6].checked) {
		sunday.start = document.getElementById('start-sunday').value;
		sunday.end = document.getElementById('end-sunday').value;
	}
	var days = {};
	days.mon = monday;
	days.tue = tuesday;
	days.wed = wednesday;
	days.thu = thursday;
	days.fri = friday;
	days.sat = saturday;
	days.sun = sunday;
	if (document.getElementById('repeat-weeks').checked)
		days.repeat_weeks = document.getElementById('weeks').value;
	else
		days.repeat_weeks = false;
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
    	if (xhttp.readyState == 4 && xhttp.status == 200) {
    		var result = JSON.parse(xhttp.responseText);
    		if (result.success) {
    			if (moment(new Date(result.last_day)).isValid())
    				document.getElementById('open-last-day').innerHTML = 'Dina arbetstider har sparats. Sista dag: ' + moment(result.last_day).format('D MMM, HH:mm');
    			$.noty.closeAll();
    			var text = 'Dina öppettider har sparats <img src="/img/happy.png" width="20" style="margin-left:5px;">';
    			setTimeout(function(){
    				noty({layout:'center', type:'success', theme:'relax', text: text}); 
    			}, 500);
    		}
    	}
  	}
  	var token = document.getElementsByTagName('meta')[1].getAttribute('content');
	xhttp.open('POST', '/set-opening-hours', true);
  	xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  	xhttp.setRequestHeader('X-CSRF-TOKEN', token);
  	xhttp.send('days=' + JSON.stringify(days));
};