<!-- Load Navbar and Script -->
<script>
    (function loadNavbar() {
        fetch('Components/Navbar.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Navbar.html does not exist or is inaccessible');
                }
                return response.text();
            })
            .then(html => {
                document.getElementById('navbar-placeholder').innerHTML = html;

                // Dynamically load app.js if not already loaded
                if (!document.querySelector('script[src="Components/app.js"]')) {
                    const script = document.createElement('script');
                    script.src = 'Components/app.js';
                    script.defer = true;
                    document.body.appendChild(script);
                }
            })
            .catch(error => {
                console.error('Error loading navbar:', error);
                document.getElementById('navbar-placeholder').innerHTML =
                    '<p style="color: red; text-align: center;">Navbar could not be loaded.</p>';
            });
    })();
</script>