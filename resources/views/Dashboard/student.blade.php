<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>@yield('title', 'UMIANKonek | Student Dashboard')</title>

  {{-- Bootstrap CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  {{-- FontAwesome & Google Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  {{-- Custom CSS --}}
  <link rel="stylesheet" href="{{ asset('/css/student.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/dashboardstudent.css') }}">

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="path/to/your/custom-script.js"></script>


  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

  {{-- NAVBAR --}}
  @include('StudentLayouts.navbarstudent')

  <div class="d-flex">
    {{-- SIDEBAR --}}
    @include('StudentLayouts.sidebarstudent')

    {{-- MAIN CONTENT --}}
    <main class="content p-4">
      @yield('content')
    </main>
  </div>

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>

  {{-- Custom Scripts --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Get all sidebar links
      const sidebarLinks = document.querySelectorAll('.sidebar-link, .sidebar-sublink');

      // Function to set active link
      function setActiveLink(clickedLink) {
        // Remove active class from all links
        sidebarLinks.forEach(link => {
          link.classList.remove('active');
        });

        // Add active class to clicked link
        clickedLink.classList.add('active');

        // If it's a sublink, also activate its parent
        if (clickedLink.classList.contains('sidebar-sublink')) {
          const parentId = clickedLink.getAttribute('data-parent');
          const parentLink = document.querySelector(`.sidebar-link[data-active="${parentId}"]`);
          if (parentLink) {
            parentLink.classList.add('active');
          }
        }

        // Store the active link in sessionStorage
        sessionStorage.setItem('activeSidebarLink', clickedLink.getAttribute('data-active'));
      }

      // Check for previously active link on page load
      const activeLinkId = sessionStorage.getItem('activeSidebarLink');
      if (activeLinkId) {
        const activeLink = document.querySelector(`[data-active="${activeLinkId}"]`);
        if (activeLink) {
          setActiveLink(activeLink);
        }
      }

      // Add click event listeners
      sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          // For dropdown toggles, don't set active if it's just opening the dropdown
          if (this.classList.contains('dropdown-toggle') && !e.target.classList.contains(
              'dropdown-item')) {
            return;
          }
          setActiveLink(this);
        });
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      const sidebarToggle = document.getElementById('sidebarToggle');
      const sidebar = document.querySelector('.sidebar');
      const content = document.querySelector('.content');

      // Toggle sidebar when button is clicked
      sidebarToggle.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent this click from reaching content
        sidebar.classList.toggle('active');
      });

      // Close sidebar when clicking on content
      content.addEventListener('click', function() {
        if (sidebar.classList.contains('active')) {
          sidebar.classList.remove('active');
        }
      });

      // Prevent clicks inside sidebar from closing it
      sidebar.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    });
  </script>

</body>

</html>