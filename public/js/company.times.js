function firstToUpperCase( str ) {
    return str.substr(0, 1).toUpperCase() + str.substr(1);
}
function JsonContainsTime(json, time) {
    for (var i = 0; i < json.length; i++) {
        if (time == json[i].timestamp.substr(11)) {
            return json[i];
        }
    }
    return false;
}
function timeTrue(data) {
    var x = document.getElementById('times');
    var div = document.createElement('div');
    div.innerHTML = data.timestamp.substr(11) + ' <span class="ion-android-remove"></span>';
    div.setAttribute('id', data.id);
    div.setAttribute('class', 'timestamp minus');
    div.setAttribute('value', data.timestamp.substr(11));
    div.addEventListener('click', manageTime, false);
    x.appendChild(div);
}
function timeFalse(time) {
    var x = document.getElementById('times');
    var div = document.createElement('div');
    div.innerHTML = time + ' <span class="ion-ios-plus-outline"></span>';
    div.setAttribute('class', 'timestamp plus');
    div.setAttribute('value', time);
    div.addEventListener('click', manageTime, false);
    x.appendChild(div);
}
// var day = document.getElementById('today');
// day.innerHTML = '<span class="ion-android-time"></span><div id="middle">' + firstToUpperCase(moment(day.getAttribute('value')).format('dddd, D MMMM')) + '</div>';
var calendar = document.getElementsByClassName('fc-day-number');
var timeToManage = document.getElementsByClassName('timestamp');
var xhttp = new XMLHttpRequest();

var dateChanged = function(){
    var currentDate = $('#calendar').fullCalendar('getDate');
    $('.fc-toolbar .fc-left h2').text(firstToUpperCase(currentDate.locale('sv').format('ddd D MMM, YYYY')));
}
$('#calendar').fullCalendar({
    height: 550,
    header: {
        left: 'title',
        right: 'prev,next'
    },
    eventAfterAllRender: dateChanged, 
    dayClick: function(date, jsEvent, view) {
        // console.log(date.format('YYYY-MM-DD'));
        xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            var times = [{id: 0, time: '09:00:00'}, {id: 1, time: '09:30:00'}, {id: 2, time: '10:00:00'}, {id: 3, time: '10:30:00'}, {id: 4, time: '11:00:00'}, {id: 5, time: '11:30:00'}, {id: 6, time: '12:00:00'}, {id: 7, time: '12:30:00'}, {id: 8, time: '13:00:00'}, {id: 9, time: '13:30:00'}, {id: 10, time: '14:00:00'}, {id: 11, time: '14:30:00'}, {id: 12, time: '15:00:00'}, {id: 13, time: '15:30:00'}, {id: 14, time: '16:00:00'}, {id: 15, time: '16:30:00'}];
            var x = document.getElementById('times');
            var day = document.getElementById('today');
            // var d = moment(date.format()).format('dddd, D MMMM');
            day.setAttribute('value', date.format('YYYY-MM-DD'));
            // day.innerHTML = firstToUpperCase(d);
            $('.fc-toolbar .fc-left h2').text(firstToUpperCase(date.locale('sv').format('ddd D MMM, YYYY')));
            x.innerHTML = '';
            x.appendChild(day);
            if (isNaN(parseInt(xhttp.responseText))) {
                var data = JSON.parse(xhttp.responseText);
                for (var i = 0; i < times.length; i++) {
                    var time = JsonContainsTime(data, times[i].time);
                    if (time) {
                        timeTrue(time);
                    }
                    else {
                        timeFalse(times[i].time);
                    }
                }
            }
            else {
                for (var i = 0; i < times.length; i++) {
                    timeFalse(times[i].time);
                }
            }
        }
    }
    xhttp.open('POST', 'mobile_api/post/search.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send('search=timestamp&timestamp=' + date.format('YYYY-MM-DD'));
    },
    eventAfterRender: function() {
        // $('.fc-week').css('height','10px');
    }
});
document.getElementsByClassName('fc-content-skeleton')[0].style.marginTop = '0px';
$('.fc-row.fc-week.fc-widget-content').css('height', '76px');
// var arrowLeft = document.createElement('div');
// var arrowRight = document.createElement('div');
// arrowLeft.setAttribute('class', 'ion-android-arrow-dropleft-circle');
// arrowRight.setAttribute('class', 'ion-android-arrow-dropright-circle');
// var fcButtonGroup = document.getElementsByClassName('fc-button-group')[0];
// $('.fc-today-button.fc-button.fc-state-default.fc-corner-left.fc-corner-right.fc-state-disabled').remove();
// $('.fc-icon.fc-icon-left-single-arrow').remove();
// $('.fc-prev-button.fc-button.fc-state-default.fc-corner-left').remove();
// $('.fc-next-button.fc-button.fc-state-default.fc-corner-right').remove();
// $('.fc-icon.fc-icon-right-single-arrow').remove();
// fcButtonGroup.appendChild(arrowLeft);
// fcButtonGroup.appendChild(arrowRight);

document.getElementsByClassName('fc-prev-button fc-button fc-state-default fc-corner-left')[0].className += ' ion-android-arrow-dropleft-circle';
document.getElementsByClassName('fc-next-button fc-button fc-state-default fc-corner-right')[0].className += ' ion-android-arrow-dropright-circle';
// document.getElementsByClassName('fc-button-group')[0].removeAttribute('class');
// var fcButton = document.getElementsByClassName('fc-button-group');
// var fcButton = document.getElementsByClassName('fc-button');
// console.log(fcButton[1].className);
// fcButton[0].className = 'ion-android-arrow-dropleft-circle';
// console.log(fcButton[0]);
// fcButton[1].className = 'ion-android-arrow-dropright-circle';
var removeTime = function() {
    var day = document.getElementById('today').getAttribute('value');
    var value = this.getAttribute('value');
    var html = this.parentNode.previousSibling;
    id = this.previousSibling.id;
    var removeElement = this.parentNode;
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            if (xhttp.responseText == 1) {
                html.innerHTML = value + ' <span class="ion-ios-plus-outline"></span>';
                html.removeAttribute('id');
                html.className = 'timestamp plus';
                $(removeElement).remove();
            }
        }
    }
    xhttp.open('POST', 'mobile_api/post/time.remove.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send('id=' + this.id + '&timestamp=' + day + ' ' + this.getAttribute('value'));
}
var removeConfirm = function() {
    this.parentNode.previousSibling.className = 'timestamp minus';
    this.parentNode.previousSibling.setAttribute('id', this.previousSibling.id);
    this.parentNode.previousSibling.innerHTML = this.parentNode.previousSibling.getAttribute('value') + ' <span class="ion-android-remove"></span>';
    $(this).parent().remove();
}
function confirmRemoveTime(element, day, timestamp) {
    var div = document.createElement('div');
    var text = document.createElement('span');
    text.innerHTML = 'Ta bort tid?';
    var yes = document.createElement('span');
    yes.setAttribute('id', element.id);
    yes.setAttribute('class', 'yes');
    yes.setAttribute('value', timestamp);
    var no = document.createElement('span');
    no.setAttribute('id', 'cancel-' + element.id);
    no.setAttribute('class', 'no');
    div.setAttribute('id', 'remove-' + element.id);
    div.setAttribute('class', 'remove');
    element.removeAttribute('id');
    element.className = 'timestamp confirm-remove';
    yes.innerHTML = 'Ja, ta bort <span class="ion-trash-a"></span>';
    no.innerHTML = 'Nej <span class="ion-ios-close"></span>';
    div.appendChild(text);
    div.appendChild(yes);
    div.appendChild(no);
    var el = {div: div, remove: yes, cancel: no};
    element.parentNode.insertBefore(div, element.nextSibling);
    return el;
}
function addTime(element, day, timestamp) {
    var day = document.getElementById('today').getAttribute('value');
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            console.log(xhttp.responseText);
            if (!isNaN(xhttp.responseText)) {
                element.id = xhttp.responseText;
                element.className = 'timestamp minus';
                element.setAttribute('value', timestamp);
                element.innerHTML = timestamp + ' <span class="ion-android-remove"></span>';
            }
        }
    }
    xhttp.open('POST', 'admin/times/add', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send('timestamp=' + day + ' ' + timestamp);
}
var manageTime = function() {
    var day = document.getElementById('today').getAttribute('value');
    id = parseInt(this.id);
    if (isNaN(id) && this.className !== 'timestamp confirm-remove') 
        addTime(this, day, this.getAttribute('value'));
    else {
        if (this.className !== 'timestamp confirm-remove') {
            var t = confirmRemoveTime(this, day, this.getAttribute('value'));
            t.remove.addEventListener('click', removeTime, false);
            t.cancel.addEventListener('click', removeConfirm, false);
        }
    }
};
for (var i = 0; i < timeToManage.length; i++) {
    timeToManage[i].addEventListener('click', manageTime, false);
}