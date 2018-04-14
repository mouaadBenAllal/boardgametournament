<footer id= "footer" class="footer-2">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-left mt-2 mb-3 pt-1">
                <p class="copyright">{{ date('Y') }} - {{ config('app.name', 'Laravel') }}</p>
            </div>
            <div class="col-md-6 text-center text-md-right mb-4">
                <ul class="social">
                    <li><a href="#" title="Facebook" class="fa fa-facebook"></a></li>
                    <li><a href="#" title="Twitter" class="fa fa-twitter"></a></li>
                    <li><a href="#" title="Google+" class="fa fa-google"></a></li>
                    <li><a href="#" title="Instagram" class="fa fa-instagram"></a></li>
                    <div class="clear"></div>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="/bjs/bootstrap.min.js"></script>
@yield('js')
