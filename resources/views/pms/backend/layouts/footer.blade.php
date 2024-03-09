<!-- Footer -->
<footer class="bg-white iq-footer mr-0">
  <div class="container-fluid">
     <div class="row">
        <div class="col-lg-6">
           <ul class="list-inline mb-0">
              <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
              <li class="list-inline-item"><a href="#">Terms of Use</a></li>
           </ul>
        </div>
        <div class="col-lg-6 text-right">
            Design & developed for <a>{{ session()->get('system-information')['organization'] }}</a> By {!! session()->get('system-information')['description'] !!}.
        </div>
     </div>
  </div>
</footer>
<!-- Footer END -->