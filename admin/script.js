// Main menu item selection
const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li > a');

allSideMenu.forEach(item => {
	const li = item.parentElement;

	item.addEventListener('click', function (e) {
		// If this menu item has a submenu, prevent navigation and toggle the submenu
		if (li.querySelector('.sub-menu')) {
			e.preventDefault();
			li.classList.toggle('show-submenu');
			
			// Make sure dropdown icons have the proper class for transition
			const dropdownIcon = item.querySelector('.dropdown-icon');
			if (dropdownIcon) {
				// The rotation is handled by CSS based on parent li.show-submenu class
				// No additional JavaScript needed for the rotation itself
			}
		} else {
			// Otherwise, handle normal menu item selection
			allSideMenu.forEach(i => {
				i.parentElement.classList.remove('active');
			});
			li.classList.add('active');
		}
	});
});

// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
});

// SEARCH FUNCTIONALITY
const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if (window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if (searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
});

// RESPONSIVE ADJUSTMENTS
if (window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if (window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}

window.addEventListener('resize', function () {
	if (this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
});

// DARK MODE TOGGLE
const switchMode = document.getElementById('switch-mode');

switchMode.addEventListener('change', function () {
	if (this.checked) {
		document.body.classList.add('dark');
	} else {
		document.body.classList.remove('dark');
	}
});