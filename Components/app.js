const collapseToggle = document.getElementById('collapseToggle');
const collapseIcon = document.getElementById('collapseIcon');
const sidebar = document.getElementById('sidebar');
const sidebarHeaderContainer = document.getElementById('sidebarHeaderContainer');
const mainContent = document.getElementById('mainContent');



// Collapse/expand sidebar and header on click
collapseToggle.addEventListener('click', () => {
    toggleSidebar();
});

function toggleSidebar() {
    // Get the sidebar, header, main content, and footer containers
    const sidebar = document.getElementById('sidebar');
    const sidebarHeaderContainer = document.getElementById('sidebarHeaderContainer');
    const mainContent = document.getElementById('mainContent');
    const stickyFooterContainer = document.getElementById('stickyFooterContainer');
    const collapseIcon = document.getElementById('collapseIcon');

    // Toggle collapsed classes for sidebar, header, main content, and sticky footer
    sidebar.classList.toggle('collapsed');
    sidebarHeaderContainer.classList.toggle('collapsed');
    mainContent.classList.toggle('shifted-collapsed');
    stickyFooterContainer.classList.toggle('collapsed');

    // Change the icon based on the sidebar state
    if (sidebar.classList.contains('collapsed')) {
        collapseIcon.classList.remove('bx-arrow-from-right');
        collapseIcon.classList.add('bx-arrow-from-left');
    } else {
        collapseIcon.classList.remove('bx-arrow-from-left');
        collapseIcon.classList.add('bx-arrow-from-right');
    }
}


// Handle custom tooltip logic and suppress native tooltip
const sidebarLinks = document.querySelectorAll('.nav_list a');

sidebarLinks.forEach(link => {
    link.addEventListener('mouseenter', function () {
        this.setAttribute('data-title', this.getAttribute('title'));
        this.removeAttribute('title');
    });

    link.addEventListener('mouseleave', function () {
        this.setAttribute('title', this.getAttribute('data-title'));
    });

    // Click event to handle sidebar expansion and navigation
    link.addEventListener('click', function (e) {
        if (sidebar.classList.contains('collapsed')) {
            e.preventDefault(); // Prevent immediate navigation
            toggleSidebar(); // Expand sidebar
            setTimeout(() => {
                window.location.href = this.getAttribute('href');
            }, 300);
        }
    });
});

// Dropdown toggle functionality with auto-collapsing
const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function (e) {
        e.preventDefault();

        const submenu = this.nextElementSibling;
        const chevron = this.querySelector('.dropdown-icon');

        const parent = this.closest('ul');
        const siblingSubmenus = parent.querySelectorAll('.submenu');
        siblingSubmenus.forEach(sibling => {
            if (sibling !== submenu && sibling.classList.contains('open')) {
                sibling.classList.remove('open');
                const siblingChevron = sibling.previousElementSibling.querySelector('.dropdown-icon');
                if (siblingChevron) siblingChevron.classList.remove('rotate-down');
            }
        });

        submenu.classList.toggle('open');
        chevron.classList.toggle('rotate-down');
        resetActiveLinks();
        this.classList.add('active');
    });
});

// Function to reset active links
function resetActiveLinks() {
    sidebarLinks.forEach(link => link.classList.remove('active'));
}

// Open the mobile nav
hamburgerIcon.addEventListener('click', function () {
    mobileNav.classList.add('open');
    overlay.classList.add('show');
});

// Close the mobile nav (via close button)
// closeNavBtn.addEventListener('click', function () {
//     mobileNav.classList.remove('open');
//     overlay.classList.remove('show');
// });

// Close the mobile nav (via overlay click)
overlay.addEventListener('click', function () {
    mobileNav.classList.remove('open');
    overlay.classList.remove('show');
});