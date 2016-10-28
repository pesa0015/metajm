$.getScript('../js/company.services.js');
function addSelect2(category) {
	$(category).select2({
	  	width: 'resolve',
	  	containerCssClass: 'tpx-select2-container',
  		dropdownCssClass: 'tpx-select2-drop',
	  	tags: true,
		maximumSelectionSize: 1,
		minimumInputLength: 1,
		formatInputTooShort: function () {
            return 'Sök';
        },
		tokenSeparators: [","],
		ajax: {
			url: '/search/category',
			type: 'POST',
			dataType: 'json',
			data: function (term) {
			    return {
			        term: term
			    };
			},
			results: function (data) {
       			var myResults = [];
       			if (data) {
				    $.each(data, function (index, item) {
				        if (isNaN(item.id)) {
				            myResults.push({
					            'id': item.id,
					            'text': item.name + ' (Ny)'
					        });
				        }
				        else {
					        myResults.push({
					            'id': item.id,
					            'text': item.name
					        });
				        }
					});
					return {
				    	results: myResults
					};
				}
			}
		}
	});
}
var newService = document.getElementById('add-new-service');
var showServiceTable = document.getElementById('show-service-table');
var serviceTable = document.getElementById('new-services');
var existingServices = document.getElementsByClassName('existing-service category');
var existingServicesTime = document.getElementsByClassName('existing-service time');
var rowNr = 0;
var updateButton = document.getElementById('update-services');
newService.onclick = function() {
	if (updateButton.style.display == 'none') {
		updateButton.style.display = 'inline-block';
		var header = serviceTable.createTHead();
		var categoryHeader = document.createElement('th');
		categoryHeader.innerHTML = 'Tjänst';
		header.appendChild(categoryHeader);
		var descriptionHeader = document.createElement('th');
		descriptionHeader.innerHTML = 'Kategori';
		header.appendChild(descriptionHeader);
		var priceHeader = document.createElement('th');
		priceHeader.innerHTML = 'Pris';
		header.appendChild(priceHeader);
		var timeHeader = document.createElement('th');
		timeHeader.innerHTML = 'Tid (min)';
		header.appendChild(timeHeader);
	}
	var row = serviceTable.insertRow(rowNr);
	var category = row.insertCell(0).innerHTML = '<input type="text" id="category-' + rowNr + '" class="new-service category" name="new_service[][\'category\']">';
	var description = row.insertCell(1).innerHTML = '<input type="text" id="description-' + rowNr + '" class="new-service description form-control" name="new_service[][\'description\']">';
	var price = row.insertCell(2).innerHTML = '<input type="text" id="price-' + rowNr + '" class="new-service price form-control" name="new_service[][\'price\']">';
	var time = row.insertCell(3).innerHTML = '<select id="time-' + rowNr + '" class="new-service time form-control" name="new_service[][\'time\']"><option value="0" selected>Välj tid</option><option value="30">30</option><option value="60">60</option><option value="90">90</option><option value="120">120</option><option value="150">150</option><option value="180">180</option></select>';
	addSelect2($('#category-' + rowNr));
	rowNr++;
};
function newServiceAdded() {
	location.reload(true);
}
var xhttp = new XMLHttpRequest();
var token = document.getElementsByTagName('meta')[1].getAttribute('content');
function sendData(file, data, callback, value, xhttpAsCallback = false) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
    	if (xhttp.readyState == 4 && xhttp.status == 200) {
    		console.log(xhttp.responseText);
    		var result = JSON.parse(xhttp.responseText);
    		console.log(result);
    		if (result.success) {
    			if (xhttpAsCallback)
    				callback(result.data);
    			else
    				callback(value);
    		}
    	}
  	}
	xhttp.open('POST', file, true);
	xhttp.setRequestHeader('X-CSRF-TOKEN', token);
  	xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  	xhttp.send(data);
}
updateButton.addEventListener('click', function() {
	var categoryArray = new Array();
	var descriptionArray = new Array();
	var priceArray = new Array();
	var timeArray = new Array();
	var categoriesInput = document.getElementsByName('new_service[][\'category\']');
	var descriptionInput = document.getElementsByName('new_service[][\'description\']');
	var priceInput = document.getElementsByName('new_service[][\'price\']');
	var timeInput = document.getElementsByName('new_service[][\'time\']');
	var start = parseInt(rowNr-categoriesInput.length);
	var array = [];
	var currentService = {};
	if (categoriesInput.length > 0) {
		for (var i = 0; i < categoriesInput.length; i++) {
			var services = {};
				services.category = categoriesInput[i].value;
				services.description = descriptionInput[i].value;
				services.price = priceInput[i].value;
				services.time = timeInput[i].value;
				array.push(services);
		}
		sendData('services/create', 'services=' + JSON.stringify(array), newServiceAdded);
	}
});
function showEditServiceInput(element) {
	var serviceId = element.getAttribute('data-service');
	var editServiceInput = document.getElementsByClassName('edit-service-' + serviceId);
	$('.service-' + serviceId).hide();
	$(editServiceInput).show();
	element.style.display = 'none';
	var okButton = document.getElementById('edit-service-' + serviceId);
	okButton.style.display = 'block';
	var name = editServiceInput[0].getAttribute('data-name');
	$(editServiceInput[0]).select2({
		width: 'resolve',
		containerCssClass: 'tpx-select2-container',
  		dropdownCssClass: 'tpx-select2-drop',
		tags: true,
		maximumSelectionSize: 1,
		minimumInputLength: 1,
		formatInputTooShort: function () {
            return 'Sök';
        },
		tokenSeparators: [","],
		ajax: {
			url: '/search/category',
			type: 'POST',
			dataType: 'json',
			data: function (term, search) {
			    return {
			        term: term
			    };
			},
			results: function (data) {
			    var myResults = [];
			    if (data) {
				    $.each(data, function (index, item) {
				        if (isNaN(item.id)) {
				            myResults.push({
					            'id': item.id,
					            'text': item.name + ' (Ny)'
					        });
				        }
				        else {
					        myResults.push({
					            'id': item.id,
					            'text': item.name
					        });
				        }
				    });
				    return {
				        results: myResults
				    };
			    }
			}
		},
		initSelection: function (element, callback) {
			var id = element[0].value;
			var data = [];
        	data.push({
		        id: id,
		        text: name
		    });
		    callback(data[0]);
		}
	});
	okButton.onclick = function() {
		var service = {};
			service.id = serviceId;
			service.category_id = editServiceInput[1].value;
			service.category_name = $(editServiceInput[0]).select2('data')[0].text;
			service.name = editServiceInput[2].value;
			service.price = editServiceInput[3].value;
			service.time = editServiceInput[4].value;
			sendData('services/edit', 'service=' + JSON.stringify(service), hideEditServiceInput, null);
		};
}
function hideEditServiceInput() {
	location.reload(true);
}
$('.edit-service-btn').click(function() {
	showEditServiceInput(this);
});