    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        // Toggle sidebar on mobile
        $(document).ready(function() {
            $('#sidebarToggle').click(function() {
                $('#sidebar').toggleClass('show');
            });
            
            // Highlight active menu item
            const currentPage = location.pathname.split('/').pop();
            $('.nav-link').each(function() {
                if ($(this).attr('href') === currentPage) {
                    $(this).addClass('active');
                }
            });
        });
    </script>
</body>
</html>