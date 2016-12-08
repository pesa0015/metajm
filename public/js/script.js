$(document).ready(function() {
  $('.tooltip').tooltipster();
});
var start = document.getElementById('start');
var getLocation = document.getElementById('get-location');
var addressOrCity = document.getElementById('address-or-city');
var search = document.getElementById('search-button');
var gMap = document.getElementById('map');
var companyList = document.getElementById('company-list');
var noPosition = document.getElementById('no-position');
var selectedCompany = document.getElementById('selected-company');
var address = document.getElementById('search-text');
var chooseDay = document.getElementById('choose-day');
var chooseCity = document.getElementById('choose-city');
var selectStylist = document.getElementById('select-stylist');

var searchResults1 = document.getElementById('first-ul');
var searchResults2 = document.getElementById('second-ul');

var booking = {'service': false, 'time': false, 'employer': false};

function checkIfBookingReady() {
  if (!booking.time) return;
  if (booking.service && booking.time.indexOf(':') != -1)
    $('#go-to-booking').removeClass('disabled').css('cursor', 'pointer').removeAttr('disabled');
}

Date.prototype.yyyymmdd = function() {
   var yyyy = this.getFullYear().toString();
   var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
   var dd  = this.getDate().toString();
   return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]); // padding
  };

d = new Date();
var now = d.yyyymmdd();
var timestamp = $('.timestamp');
// chooseDay.placeholder += ', ' + timestamp;
var xhttp = new XMLHttpRequest();
var selectTime = document.getElementById('select-time');
var availableTimes = ['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00'];
for (var i = 0; i < availableTimes.length; i++) {
  var listItem = document.createElement('li');
  listItem.innerHTML = availableTimes[i];
  selectTime.appendChild(listItem);
}
function showCompanyPage(fadeIn) {
  if (fadeIn) {
    $(noPosition).fadeOut(1000);
    $('#video').fadeOut(1000);
  }
  else {
    noPosition.style.display = 'none';
    document.getElementById('video').style.display = 'none';
    document.getElementsByClassName('arrow bounce')[0].className += ' not';
  }
  document.getElementById('a').style.display = 'inline-block';
  document.getElementById('fade').style.display = 'inline-block';
  selectedCompany.style.display = 'inline-block';
  document.getElementById('start').style.background = '#2A2431';
  document.getElementById('start-background').style.position = 'absolute';
}
function hideCompanyPage() {
  noPosition.style.display = 'block';
  document.getElementById('video').style.display = 'block';
  document.getElementById('a').style.display = 'none';
  document.getElementById('fade').style.display = 'none';
  selectedCompany.style.display = 'none';
  document.getElementById('start').style.background = 'none';
  document.getElementById('start-background').style.position = 'relative';
}
function firstToUpperCase( str ) {
    return str.substr(0, 1).toUpperCase() + str.substr(1);
}
var circle;
var map;
function gmap(map, lat, lng) {
  marker = new google.maps.Marker({
      position: new google.maps.LatLng(lat, lng),
      map: map,
      draggable: true
    });
  circle = new google.maps.Circle({
          map: map,
          strokeColor: '#8E1C01',
          strokeOpacity: 0.3,
          strokeWeight: 2,
          fillColor: '#FF0000',
          fillOpacity: 0.2,
          radius: 2000
        });
        circle.bindTo('center', marker, 'position');
        google.maps.event.addListener(marker, 'dragend', function(a) {
    getAddressFromMarker(marker.getPosition());
  });
}
function initialize(lat, lng, companies) {
  console.log(companies);
  var mapCanvas = document.getElementById('map');
  var mapOptions = {
    center: new google.maps.LatLng(lat, lng),
    zoom: 13,
    scrollwheel: false,
    styles: [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#6195a0"}]},{"featureType":"administrative.province","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"lightness":"0"},{"saturation":"0"},{"color":"#f5f5f2"},{"gamma":"1"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"lightness":"-3"},{"gamma":"1.00"}]},{"featureType":"landscape.natural.terrain","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#bae5ce"},{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45},{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#fac9a9"},{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels.text","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#787878"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"transit.station.airport","elementType":"labels.icon","stylers":[{"hue":"#0a00ff"},{"saturation":"-77"},{"gamma":"0.57"},{"lightness":"0"}]},{"featureType":"transit.station.rail","elementType":"labels.text.fill","stylers":[{"color":"#43321e"}]},{"featureType":"transit.station.rail","elementType":"labels.icon","stylers":[{"hue":"#ff6c00"},{"lightness":"4"},{"gamma":"0.75"},{"saturation":"-68"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#eaf6f8"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#c7eced"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":"-49"},{"saturation":"-53"},{"gamma":"0.79"}]}],
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }

  var map = new google.maps.Map(mapCanvas, mapOptions);
  var infowindow = new google.maps.InfoWindow();
  if (companies) {
  for (i = 0; i < companies.length; i++) {  
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(companies[i].lat, companies[i].lng),
      map: map
    });
    google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function() {
        infowindow.setContent(companies[i].name);
        infowindow.open(map, marker);
      }
    })(marker, i));
  }
  }
  gmap(map, lat, lng);
  
var addressPicker = new AddressPicker();

$('#address').typeahead(null, {
  displayKey: 'description',
  source: addressPicker.ttAdapter()
});
addressPicker.bindDefaultTypeaheadEvent($('#address'));
$(addressPicker).on('addresspicker:selected', function (event, result) {
  marker.setMap(null);
  circle.setMap(null);
  gmap(map, result.latitude, result.longitude);
});
}
var lat = 0;
var lng = 0;
var radius = 5000;

var showCompanies = document.getElementById('show-companies');
var company_marker = new google.maps.Marker({});
var infowindow = new google.maps.InfoWindow();

var serviceChosen = false;

var companyId = document.getElementById('company-name');
function loadCalendarEvents(serviceId) {
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      // console.log(xhttp.responseText);
      var result = JSON.parse(xhttp.responseText);
      var days = result.days;
      var events = [];
      for (var i = 0; i < days.length; i++) {
        var event = {};
        event.Title = '';
        event.Date = new Date(result.days[i].start.replace(/-/g, '/').substring(0,10));
        events.push(event);
      }
      activateCalendar(companyId.getAttribute('data-company-id'), events);
    }
  }
  xhttp.open('POST', '/get/times', true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send('company_id=' + companyId.getAttribute('data-company-id') + '&service_id=' + serviceId);
  // xhttp.send('company_id=3605&service_id=1');
  // console.log('company_id=3605&timestamp== ' + $('.timestamp').val());
}
function getTimestamp() {
  if (moment(new Date($(timestamp).val())).isValid()) {
    var string = '=';
    var date = '= ' + $(timestamp).val();
    // console.log($(timestamp).val());
    var obj = {};
    var test = null;
    obj[test] = date;
    string += ' ' + JSON.stringify(moment(obj[test]).format('YYYY-MM-DD')).replace(/"/g, "'");
    // string += ' ' + JSON.stringify(moment(obj[test]).format('YYYY-MM-DD'));
    return string;
  }
  else
    return '>= CURDATE()';
  
}
var geocoder = new google.maps.Geocoder;
mapAddress = document.getElementById('address');
function getAddressFromMarker(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      // mapAddress.value = responses[0].formatted_address;
      $('#address').val(responses[0].formatted_address);
    } else {
      updateMarkerAddress('Cannot determine address at this location.');
    }
  });
}
function getCityName(latitude, longitude) {
  var coords = {lat: parseFloat(latitude), lng: parseFloat(longitude)};

  if (geocoder) {
    geocoder.geocode({ 'location': coords }, function (results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        // address.value = results[1].formatted_address;
        // lat.value = latitude;
        // lng.value = longitude;
        // console.log(results[1]);
        mapAddress.value = results[1].formatted_address;
        // return results[1].formatted_address;
      }
      else {
        console.log('Geocoding failed: ' + status);
      }
    });
   }
}
function getLatLng() {
  if (navigator.geolocation) {
    // getLocation.value = 'Söker..';
    navigator.geolocation.getCurrentPosition(function(position) {
      // getLocation.value = 'Position hittad';
      console.log('Position hittad');
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      lat = pos.lat;
      lng = pos.lng;
      // getCityName(pos.lat, pos.lng);
    });
  }
}
var mapModal = document.getElementById('map-modal');
chooseCity.onchange = function() {
  var selected = this.options[this.selectedIndex];
  getCityName(selected.getAttribute('data-lat'), selected.getAttribute('data-lng'));
  lat = parseFloat(selected.getAttribute('data-lat'));
  lng = parseFloat(selected.getAttribute('data-lng'));
}
document.getElementById('radius').onchange = function() {
  circle.setRadius(parseInt(this.value));
  radius = this.value;
  // positionCheckbox();
}

var ulLength1 = $(searchResults1);
var ulLength2 = $(searchResults2);
var list = document.getElementById('results');
function checkArray(array, a) {
  var html = '';
  $('#results').empty();
  for (var i = 0; i < array.length; i++) {
    html += '<li class="search-item-result">' + array[i] + '</li>';
  }
  // document.getElementById('results').innerHTML = html;
  $('#results').append(html);
}

address.onkeydown = function(e) {
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      // console.log(xhttp.responseText);
      var result = JSON.parse(xhttp.responseText);
      var companies = result.companies;
      var categories = result.categories;
      if (e.keyCode != 40 && e.keyCode != 38)
        $(list).empty();
      list.style.display = 'block';
      if (result.length > 0) {
        address.style.borderBottomLeftRadius = '0';
        address.style.borderBottomRightRadius = '0';
      }
      for (var i = 0; i < companies.length; i++) {
        $(list).append('<li class="search-item-result">' + companies[i].name + '</li>');
      }
      for (var i = 0; i < categories.length; i++) {
        $(list).append('<li class="search-item-result">' + categories[i].name + '</li>');
      }      
    }
  }
  var $hlight = $('li.hlight'), $div = $('li.search-item-result');
      if (e.keyCode == 40) {
          if ($hlight.next().length == 0) {
              $div.eq(0).addClass('hlight');
          }
          $hlight.removeClass('hlight').next().addClass('hlight');
      } else if (e.keyCode == 38) {
          $hlight.removeClass('hlight').prev().addClass('hlight');
          if ($hlight.prev().length == 0) {
              $div.eq(-1).addClass('hlight');
          }
      }
  if (e.keyCode == 40 || e.keyCode == 38) {
    list.style.display = 'block';
    this.value = document.getElementsByClassName('hlight')[0].innerHTML;
    return;
  }
  if (this.value.length > 2) {
    xhttp.open('POST', '/search/live-search', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    // xhttp.setRequestHeader('X-CSRF-TOKEN', token);
    xhttp.send('search=' + this.value);
  }
  else {
    address.style.borderBottomLeftRadius = '50px';
    address.style.borderBottomRightRadius = '50px';
    list.style.display = 'none';
  }
}
address.onchange = function() {
  if (this.value.length == 0) {
    address.style.borderBottomLeftRadius = '50px';
    address.style.borderBottomRightRadius = '50px';
    list.style.display = 'none';
  }
}
var servicesList = document.getElementById('services');
function showServices(services, employers) {
  $(servicesList).empty();
  console.log(services);
  if (services.length > 0) {
        var nextCategory = false;
        var previousCategory = null;
        for (var i = 0; i < services.length; i++) {
          nextCategory = (previousCategory !== services[i].category) ? true : false;
          if (nextCategory) {
            $(chooseServiceList).append('<div class="service-category">' + services[i].category + '</div>');
            $(chooseServiceList).append(renderService(services[i].id, services[i].name, services[i].price, services[i].time));
            // document.getElementsByClassName('label-service')[i].addEventListener('click', serviceCheckbox, false);
            document.getElementsByClassName('label-service')[i].onclick = function(e) {
              callCheckbox(e, 'service');
            }
          }
          else {
            $(chooseServiceList).append(renderService(services[i].id, services[i].name, services[i].price, services[i].time));
            document.getElementsByClassName('label-service')[i].onclick = function(e) {
              callCheckbox(e, 'service');
            }
          }
          previousCategory = services[i].category;
        }
      }
    if (employers.length > 0) {
      $(selectStylist).empty();
        for (var i = 0; i < employers.length; i++) {
          $(selectStylist).append(renderEmployer(employers[i].id, employers[i].first_name + ' ' + employers[i].last_name));
          document.getElementsByClassName('label-employer')[i].onclick = function(e) {
              callCheckbox(e, 'employer');
            }
        }
      }
}
function getServicesFromCalendar(companyId, date) {
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      // console.log(xhttp.responseText);
      var data = JSON.parse(xhttp.responseText);
      if (data.not_open) {
        return;
      }
      console.log(data);
      var services = data.services;
      var employers = data.employers;
      showServices(services, employers);
    }
  }
  // console.log(getTimestamp());
  xhttp.open('POST', '/get/services', true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send('company_id=' + companyId + '&timestamp=' + getTimestamp());
}
function getHours(date) {
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      var data = JSON.parse(xhttp.responseText);
      if (!data.success)
        return;

      $(selectTime).empty();
      var hours = data.times;
      for (var i = 0; i < hours.length; i++) {
        $(selectTime).append('<li>' + moment(hours[i].timestamp).format('HH:mm') + '</li>');
      }
    }
  }
  xhttp.open('POST', '/get/hours', true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send('date=' + date + '&company_id=' + companyId.getAttribute('data-company-id') + '&service_id=' + booking.service + '&employer_id=' + booking.employer);
}
function activateCalendar(companyId, events, destroy) {
  var reloadCalendar = destroy || false;
  console.log(events);
  if (reloadCalendar) {
    $(timestamp).val('');
    $(timestamp).datepicker('destroy');
  }
  $(timestamp).datepicker({
    dateFormat: 'yy-mm-dd',
    firstDay: 1,
    minDate: 0,
    beforeShowDay: function(date) {
          var matching = $.grep(events, function(event) {
            return event.Date.valueOf() === date.valueOf();
          });

          return (matching.length) ? [true, 'available-day', null] : [false, false, null];
        },
    onSelect: function(date) {
      $(timestamp).next('i').show();
      console.log(date);
      $(timestamp).val(date);
      booking.time = date;
      checkIfBookingReady();
      if (!booking.service)
        getServicesFromCalendar(companyId);
      getHours(date);
    }
  });
}
var chooseServiceList = document.getElementById('services');
function checkCheckbox(checkbox, type) {
  if (!checkbox.checked) {
    checkbox.checked = true;
    booking[type] = checkbox.value;
    checkIfBookingReady();
    if (!booking.time)
      loadCalendarEvents(checkbox.value);
  }
  else {
    checkbox.checked = false;
  }
}
function callCheckbox(element, type) {
  var target = (event.target.nodeName === 'SPAN') ? event.target.parentElement : event.target;
  if (target.className.indexOf('label') >= 0) {
    checkCheckbox(target.previousSibling, type);
  }
}
function getCompanyInfo(company_id) {
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      var data = JSON.parse(xhttp.responseText);
      if (parseInt(data[0].employers_visible) == 1)
        document.getElementById('choose-stylist').style.display = 'block';
      else
        document.getElementById('choose-stylist').style.display = 'none';
    }
  }
  xhttp.open('POST', baseUrl + '/mobile_api/post/company_info.php', false);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send('company_id=' + company_id);
}
function renderService(id, description, price, time) {
  var nr = time / 60;
  var time_string = (nr % 1 == 0) ? nr + 'h' : time + ' min'
  return '<input type="radio" name="service" value="' + id +'" class="input-service"><label class="label-service" for="service"><span class="service-description">' + description + '</span><span class="service-price"> ' + price + ' kr</span><span class="service-time">' + time_string + '</span><i class="ion-plus"></i><i class="ion-minus"></i></label>';
}
function renderEmployer(id, name) {
  return '<input type="radio" name="employer" value="' + id +'" class="input-employer"><label class="label-employer label" for="employer">' + name + '</label>';
}
function getServices(company_id, company_data, days_available, day, fadeInCompanyPage) {
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      console.log(xhttp.responseText);
      var data = JSON.parse(xhttp.responseText);
      
      var fade = fadeInCompanyPage || false;
      var company = data.company;
      showCompanyPage(fade);
      var services = data.services;
      var employers = data.employers;
      var open = data.hours;
      console.log(company.name);
      showServices(services, employers);
        if (days_available) {
          var events = [];
          for (var i = 0; i < days_available.length; i++) {
            var event = {};
            event.Title = '';
            event.Date = new Date(days_available[i].start.replace(/-/g, '/').substring(0,10));
            events.push(event);
          }
        }
        if (day && moment(day.open).isValid()) {
          $('#hour-start').append(moment(day.open).format('HH:mm'));
          $('#hour-close').append(moment(day.close).format('HH:mm'));
        }
        else {
          document.getElementById('open').innerHTML = 'Ej öppet idag';
        }
        
        // selectableHours = data.times;
        activateCalendar(company_id, events, true);
        // $(chooseServiceList).append('<div id="go-to-booking">Boka <i class="ion-checkmark-round"></i></div>');
      
      document.getElementById('selected-company').style.display = 'block';
      document.getElementById('company-name').innerHTML = company.name;
      document.getElementById('company-name').setAttribute('data-company-id', company.id);
      document.getElementById('company-address').innerHTML = company.address;
      document.getElementById('company-tel').getElementsByTagName('span')[0].innerHTML = company.tel;
      document.getElementById('company-mail').getElementsByTagName('span')[0].innerHTML = company.mail;
      // start.style.backgroundImage = 'url(img/2.jpg)';
      $('html, body').animate({scrollTop: 0}, 1500);
    }
  }
  xhttp.open('POST', '/get/services', true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send('company_id=' + company_id + '&timestamp=' + getTimestamp());
}
document.body.onclick = function() {
  if (!address.activeElement) {
    document.getElementById('results').style.display = 'none';
  }
  else {
    document.getElementById('results').style.display = 'block';
  }
}

search.onclick = function(e) {
  e.preventDefault();
  address.focus();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      console.log(xhttp.responseText);
      var result = JSON.parse(xhttp.responseText);
      if (result.error) {
        if (result.position_missing) {
          $('#cities-and-day').find('i').show();
        }
        var message = noty({
          text: result.error_message,
          type: 'information',
          maxVisible: 1,
          layout: 'center',
          theme: 'relax'
        });
      }
      if (result.go_to_company) {
        getServices(result.company[0].id, result.company[0], result.days_available, result.day[0], true);
      }
      if (result.show_google_maps) {
          if (result.not_found) {
            var message = noty({
              text: result.error_message,
              type: 'information',
              maxVisible: 1,
              layout: 'center',
              theme: 'relax'
            });
            gMap.style.display = 'block';
            gMap.style.width = '100%';
            initialize(lat, lng, false);
          }
          else {
          document.getElementById('custom-address').style.display = 'block';
          gMap.style.display = 'block';
          companyList.style.display = 'block';
          initialize(lat, lng, result.companies);
          document.getElementsByClassName('arrow bounce not')[0].className = 'arrow bounce';
          function isEven(n) {
            return (n % 2 == 0) ? 'even' : 'odd';
          }

          for (var i = 0; i < result.companies.length; i++) {
            $(companyList).append('<div class="company company-' + isEven(i) + '" data-id="' + result.companies[i].id + '"><h3 class="company-name">' + result.companies[i].name + '</h3><p class="company-address">' + result.companies[i].address + '</p><p class="company-postalcode">' + result.companies[i].postal_code.substring(0,3) + ' ' + result.companies[i].postal_code.substring(3,5) + ' ' + result.companies[i].city +'</p></div>');
            document.getElementsByClassName('company')[i].onclick = function() {
              // getCompanyInfo(this.getAttribute('data-id'));
              getServices(this.getAttribute('data-id'), this.childNodes);
            };
          }
        }
      }
    }
  }
  if (address.value.length > 2) {
    xhttp.open('POST', '/search/main-search', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send('search=' + address.value + '&timestamp=' + getTimestamp() + '&lat=' + lat + '&lng=' + lng + '&radius=1000');
  }
};
function renderStyle(event, padding, color) {
  event.target.style.padding = padding
  event.target.style.color = color;
}
var chooseTime = document.getElementById('choose-time');
var datePicker = document.getElementById('date-picker');
document.getElementById('go-to-days').onclick = function() {
  $(datePicker).toggle();
};

function theDate(d) {
  $(timestamp).datepicker('setDate', new Date(d));
}
$(timestamp).datepicker({
  dateFormat: 'yy-mm-dd',
  firstDay: 1,
  onSelect: function(date) {
    theDate(date);
    // loadCalendarEvents();
    $(timestamp).next('i').show();
  }
});
$(timestamp).next('i').click(function() {
  $.datepicker._clearDate(timestamp);
  $(timestamp).val('Alla dagar');
  $(this).hide();
});
var goToTimes = document.getElementById('go-to-times');
goToTimes.addEventListener('click', function() {
  $(selectTime).toggle();    
});
selectTime.addEventListener('click', function(event) {
  booking.time += ' ' + event.target.innerHTML;
  checkIfBookingReady();
  goToTimes.innerHTML = event.target.innerHTML + ' <i class="ion-android-close"></i>';
  selectTime.style.display = 'none';
});

// document.getElementById('book').onclick = function() {
//     var booking = {};
//         booking.datetime = $(timestamp).val();
//         booking.fname = document.getElementById('fname').value;
//         booking.lname = document.getElementById('lname').value;
//         booking.mail = document.getElementById('mail').value;
//         booking.tel = document.getElementById('tel').value;
//         booking.service = document.querySelector('input[name=service]:checked').value;
//         console.log(booking);
//         return;
//         var xhttp = new XMLHttpRequest();
//         xhttp.onreadystatechange = function() {
//             if (xhttp.readyState == 4 && xhttp.status == 200) {
//                 console.log(xhttp.responseText);
//                 console.log(booking);
//                 if (isNaN(parseInt(xhttp.responseText))) {
//                     var error = JSON.parse(xhttp.responseText);
//                     var n = noty({layout:'center',type:'error',text:'Det gick inte att boka kl ' + error.start + '-' + error.end + ' eftersom ' + error.timeBooked[0].start.substring(11,16) + ' är upptaget'});
//                 }
//                 else {
//                     // location.reload(true);
//                     getOpeningHours(timeToBook.substring(0,10));
//                     $(modalDialog).removeClass('md-show');
//                     var n = noty({layout:'center',type:'success',text:'Bokning genomförd<i class="ion-checkmark-circled" style="margin-left:5px;"></i>'});
//                 }
//             }
//         }
//         xhttp.open('POST', 'mobile_api/post/bookings.set.php', true);
//         xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
//         xhttp.send('booking=' + JSON.stringify(booking));
// }
var bookModal = document.getElementById('confirm-modal');
var loaderModal = document.getElementById('loader-modal');
function doBooking() {
  var company_id = document.getElementById('company-name').getAttribute('data-company-id');
  var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var result = JSON.parse(xhttp.responseText);
                if (result.success) {
                  document.getElementById('wait').className = 'showbox display-block';
                  document.getElementById('start_booking').value = 'Laddar..';
                  document.getElementById('payment').innerHTML = result.go_to_payment;
                  document.getElementById('paymentForm').submit();
                }
              }
                
            }
        console.log(xhttp);
        xhttp.open('POST', '/booking/do', true);
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.setRequestHeader('X-CSRF-TOKEN', token);
        xhttp.send('company=' + company_id + '&service=' + booking.service + '&time=' + booking.time);  
}
function startBooking() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                console.log(xhttp.responseText);
                var result = JSON.parse(xhttp.responseText);
                if (result.is_logged_in) {
                  var user = result.user;
                  document.getElementById('company').innerHTML = document.getElementById('company-name').innerHTML;
                  document.getElementById('address').innerHTML = document.getElementById('company-address').innerHTML;
                  document.getElementById('my-name').innerHTML = user.first_name + ' ' + user.last_name;
                  document.getElementById('my-email').innerHTML = user.email;
                  document.getElementById('my-tel').innerHTML = user.phone_number;
                  document.getElementById('start_booking').onclick = function(e) {
                    e.preventDefault();
                    doBooking();
                  }

                  bookModal.className += ' md-show';
                  document.getElementsByClassName('md-overlay')[0].onclick = function() {
                    bookModal.className = 'md-modal md-effect-1';
                  }
                }
                else {
                  document.getElementById('login-action').value = 'to_booking';
                  document.getElementById('login-modal').className += ' md-show';
                  bookModal.className = 'md-modal md-effect-1';
                  document.getElementsByClassName('md-overlay')[0].onclick = function() {
                    document.getElementById('login-modal').className = 'md-modal md-effect-1';
                  }
                }
                
            }
        }
        console.log(xhttp);
        xhttp.open('POST', '/booking/start', true);
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.setRequestHeader('X-CSRF-TOKEN', token);
        xhttp.send();
}
document.getElementById('go-to-booking').onclick = function() {
  startBooking();   
}
var token = document.getElementById('csrf-token').getAttribute('data-value');
document.getElementById('login-form').onsubmit = function(e) {
  e.preventDefault();
  var email = document.getElementById('email');
  var password = document.getElementById('password');
  var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                console.log(xhttp.responseText);
                var result = JSON.parse(xhttp.responseText);
                if (result.success) {
                  var action = document.getElementById('login-action').value;
                  if (action === 'to_booking') {
                    startBooking();
                  }
                }
                
            }
        }
        xhttp.open('POST', '/auth/private', true);
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.setRequestHeader('X-CSRF-TOKEN', token);
        xhttp.send('email=' + email.value + '&password=' + password.value);  
}