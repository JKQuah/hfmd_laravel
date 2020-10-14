<!-- Footer -->
<footer class="footer">
    <p>Hand, Foot, Mouth Disease Dashboard | FCSIT, Universtiy Malaya | 2020 - 2021</p>
    <p>&copy All rights reserved | 2020 Copyright</p>
    <!-- javascript -->

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
        //check the page is loaded or not
        
        document.onreadystatechange = function() {
            if (document.readyState == "complete") {
                $(".loading-screen").removeClass('loading-active');
                console.log("fully loaded")
            }
        }
        $(window).on('beforeunload', function() {
            $(".loading-screen").addClass('loading-active');
        });
    </script>
    
</footer>