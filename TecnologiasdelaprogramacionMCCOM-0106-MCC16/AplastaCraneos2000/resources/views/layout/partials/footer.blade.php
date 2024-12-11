<footer>
  <div class="container">
    <div class="row p-5">
      <div class="col-12 col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 vcard">
        <p>Contact email</p>
        <a class="w-100 email" href="mailto:tung.42@gmail.com">tung.42@gmail.com</a>
        <p class="mt-3"><i class="fal fa-copyright"></i> Copyright <a class="url fn" target="_blank" href="https://tungpham42.github.io/">Tung Pham</a></p>
      </div>
      <div class="col-12 col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
        <ul class="list-unstyled">
          <li>
            <a class="home" href="{{ url('/') }}"><i class="fal fa-home-lg-alt"></i> Home</a>
          </li>
          <li>
            <a class="room" href="{{ url('/rooms') }}"><i class="fal fa-th-list"></i> Rooms</a>
          </li>
          <li>
            <a class="setup" href="{{ url('/puzzle') }}"><i class="fal fa-puzzle-piece"></i> Puzzle</a>
          </li>
          <li>
            <a class="about" href="{{ url('/about-us') }}"><i class="fal fa-info-square"></i> About Us</a>
          </li>
          <li>
            <a class="contact" href="{{ url('/contact-us') }}"><i class="fal fa-envelope"></i> Contact Us</a>
          </li>
          <li>
            <a target="_blank" class="buy" href="https://www.codester.com/items/23609/chess-game-with-ai-php-script?ref=tungpham"><i class="fal fa-shopping-cart"></i> Buy</a>
          </li>
        </ul>
      </div>
      <div class="col-12 col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
        <p>Find us on social media</p>
        <a class="w-100 mr-2 display-4" target="_blank" href="https://www.facebook.com/CoVuaPage/"><i class="fab fa-facebook-square rounded"></i></a>
        <a class="w-100 mr-2 display-4" target="_blank" href="https://www.linkedin.com/company/chessroom/"><i class="fab fa-linkedin rounded"></i></a>
      </div>
      <div class="col-12 col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
        <p>Verified with HTML5</p>
        <a title="Valid HTML 5" class="w-100 mr-2 display-4" target="_blank" href="https://validator.w3.org/check?uri=referer">
          <i class="fab fa-html5"></i>
        </a>
      </div>
    </div>
  </div>
</footer>
<div class="modal fade" id="AdBlockModal" tabindex="-1" role="dialog" aria-label="AdBlock" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow-lg">
      <div class="modal-header">
        <h5 class="modal-title">AdBlock detected</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Sure, ad-blocking software does a great job at blocking ads, but it also blocks some useful and important features of our website. For the best possible site experience please take a moment to disable your AdBlocker.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="AdSenseModal" tabindex="-1" role="dialog" aria-label="AdSense" aria-hidden="true" data-backdrop="static" data-keyboard="false" data-url="">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow-lg">
      <div class="modal-header">
        <h5 class="modal-title"><i class="far fa-ad"></i> Advertisement</h5>
      </div>
      <div class="modal-body text-center">
        <!-- CHESS_300x300 -->
        <ins class="adsbygoogle"
            style="display:inline-block;width:300px;height:300px"
            data-ad-client="ca-pub-3585118770961536"
            data-ad-slot="8859288878"></ins>
        <a class="aff-link" href="https://game.cotuong.top" target="_blank" data-toggle="tooltip" data-placement="top" title="Game"><img alt="Game" src="{{ url('/') }}/img/games.jpg"  width="300" height="300" ></a>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
      </div>
      <div class="modal-footer">
        <button id="adModalCloseBtn" type="button" class="btn btn-dark btn-lg disabled" data-dismiss="modal" data-toggle="tooltip" data-placement="top"><i class="fal fa-clock"></i> <span>2 seconds</span></button>
      </div>
    </div>
  </div>
</div>
<script>
$('.menu-toggle').on('click', function(){
  if ($(this).hasClass('open')) {
    $(this).removeClass('open').removeClass('close').addClass('close');
  } else if ($(this).hasClass('close')) {
    $(this).removeClass('close').removeClass('open').addClass('open');
  }
});
$('.showPromotion').each(function(e){
  $(this).on('click auxclick', function(e){
    if (removeTrailingSlash($(this).attr('href')) !== removeTrailingSlash(window.location.href)) {
      e.preventDefault();
      $('#AdSenseModal').attr('data-url', $(this).attr('href')).modal('show');
    } else {
      window.location.href = $(this).attr('href');
    }
  });
});
$('#AdSenseModal').on('show.bs.modal', function(){
  if (!$('#AdSenseModal').find('ins').attr('data-ad-status')) {
    $('#AdSenseModal').find('ins').attr('data-ad-status', 'unfilled');
  }
}).on('shown.bs.modal', function() {
  $('#adModalCloseBtn').attr('data-original-title', $(this).attr('data-url')).css('cursor', 'wait').prop('disabled', true);
  $('#adModalCloseBtn').tooltip();
  let i = 2;
  let timer = setInterval(function() {
    console.log(--i);
    $('#adModalCloseBtn').find('span').text(i + ' second' + (i > 1 ? 's': ''));
    if (i === -1) {
      $('#adModalCloseBtn').find('i').removeClass('fa-clock').addClass('fa-link');
      $('#adModalCloseBtn').css('cursor', 'pointer').removeClass('disabled').removeAttr('disabled').addClass('pulse-red').find('span').text('Redirect now');
      clearInterval(timer);
    }
  }, 1000);
}).on('hidden.bs.modal', function() {
  $('#adModalCloseBtn').find('span').text('2 seconds');
  window.open($(this).attr('data-url'), '_blank');
});
// Function called if AdBlock is not detected
function adBlockNotDetected() {
//	alert('AdBlock is not enabled');
}
// Function called if AdBlock is detected
function adBlockDetected() {
	$('#AdBlockModal').modal();
}
$('#AdSenseModal').on('hide.bs.modal', function(e) {
  window.location.href = $(this).attr('data-url');
})
// detectAdblock(function(isUsingAdblock) {
//   if (isUsingAdblock === true) {
//     adBlockDetected();
//   }
// });
</script>
<a href="#0" class="cd-top js-cd-top rounded" style="background-image: url({{ URL::to('/') }}/img/cd-top-arrow.svg);">Top</a>
<script src="{{ URL::to('/') }}/js/to-top.js"></script>