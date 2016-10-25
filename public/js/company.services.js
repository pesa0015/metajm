function useServiceChecked(checkbox) {
	$.noty.closeAll();
    setTimeout(function(){
		var n = noty({theme:'relax',layout:'topCenter',type:'success',text:'Tjänsten har lagts till din lista'});
	}, 500);
}
function dontUseServiceChecked(checkbox) {
	$.noty.closeAll();
    	setTimeout(function(){
	var n = noty({theme:'relax',layout:'topCenter',type:'information',text:'Tjänsten har <span class="bold">tagits bort</span> från din lista'});
	}, 500);
}
var servicesCheckbox = document.getElementsByClassName('service-checkbox');
var checkboxServiceId = null;
for (var i = 0; i < servicesCheckbox.length; i++) {
	servicesCheckbox[i].addEventListener('click', function() {
		checkboxServiceId = this.getAttribute('data-id');
		if (this.checked)
			sendData('services/use', 'use=true&id=' + checkboxServiceId, useServiceChecked, this);
		else
			sendData('services/use', 'use=false&id=' + checkboxServiceId, dontUseServiceChecked, this);
	});
}