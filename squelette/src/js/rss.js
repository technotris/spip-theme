window.onload = function() {
	// Ugly but works ;)
	// so the html content interpreted
	var contentdiv = document.querySelectorAll('div.item-content');
	contentdiv.forEach((item) => {
		let text = '';
		if (item.textContent) {
			text = item.textContent;
		} else if (item.innerText) {
			text = item.innerText;
		}
		if (text) {
			item.innerHTML = text; 
		}
	});
};