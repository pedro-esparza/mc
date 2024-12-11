<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/js/bootstrap.bundle.min.js" integrity="sha512-mULnawDVcCnsk9a4aG1QLZZ6rcce/jSzEGqUkeOLy0b6q0+T6syHrxlsAGH7ZVoqC93Pd0lBqd6WguPWih7VHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js" integrity="sha256-tSRROoGfGWTveRpDHFiWVz+UXt+xKNe90wwGn25lpw8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" integrity="sha256-0rguYS0qgS6L4qVzANq4kjxPLtvnp5nn2nB5G1lWRv4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js" integrity="sha512-RdSPYh1WA6BF0RhpisYJVYkOyTzK4HwofJ3Q7ivt/jkpW6Vc8AurL1R+4AUcvn9IwEKAPm/fk7qFZW3OuiUDeg==" crossorigin="anonymous"></script>
<script src="{{ URL::to('/') }}/js/jquery-ui/jquery-ui.min.js"></script>
<script src="{{ URL::to('/') }}/js/detect_adblock.js?v=1"></script>
<script src="{{ URL::to('/') }}/js/scripts.js?v=2"></script>
<script src="{{ URL::to('/') }}/js/manipulation.js"></script>
@desktop
<script src="{{ URL::to('/') }}/js/chessboard.js?v=16"></script>
@elsedesktop
<script src="{{ URL::to('/') }}/js/chessboard_mobile.js"></script>
@enddesktop
<script src="{{ URL::to('/') }}/js/chess.js?v=10"></script>
<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
$('#create-public-room').on('click', function(e) {
  e.preventDefault();
  bootbox.prompt({
    title: "Please create a name for your new Room:",
    locale: 'en',
    centerVertical: true,
    closeButton: false,
    maxlength: 32,
    buttons: {
      confirm: {
        label: '<i class="fas fa-check"></i> Oki',
        className: 'btn-light'
      }
    },
    callback: function(roomName){
      if (roomName != null) {
        if (roomName.trim().length === 0 || roomName.length === 0) {
          bootbox.alert({
            message: "Please enter a name.",
            size: 'small',
            locale: 'en',
            centerVertical: true,
            closeButton: false,
            buttons: {
              ok: {
                className: 'btn-light'
              }
            },
            callback: function () {
              $('#create-public-room').trigger('click');
            }
          });
        } else {
          $.ajax({
            type: "POST",
            url: '{{ url('/api') }}/createRoom',
            data: {
              'room-code': $('#create-room').attr('data-room'),
              'room-name': roomName,
              'FEN': 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1',
              'pass': ''
            },
            dataType: 'text'
          }).done(function() {
            $('#AdSenseModal').attr('data-url', $('#create-room').attr('data-url')).modal('show');
            $('#adModalCloseBtn').attr('data-original-title', $('#AdSenseModal').attr('data-url'));
            $('#adModalCloseBtn').tooltip();
          });
        }
      }
    }
  });
});
$('#create-private-room').on('click', function(e) {
  e.preventDefault();
  bootbox.prompt({
    title: "Please create a name for your new Room:",
    locale: 'en',
    centerVertical: true,
    closeButton: false,
    maxlength: 32,
    buttons: {
      confirm: {
        label: '<i class="fas fa-check"></i> Oki',
        className: 'btn-light'
      }
    },
    callback: function(roomName){
      if (roomName != null) {
        if (roomName.trim().length === 0 || roomName.length === 0) {
          bootbox.alert({
            message: "Please enter a name.",
            size: 'small',
            locale: 'en',
            centerVertical: true,
            closeButton: false,
            buttons: {
              ok: {
                className: 'btn-light'
              }
            },
            callback: function () {
              $('#create-private-room').trigger('click');
            }
          });
        } else {
          bootbox.prompt({
            title: "Please create a password for your new Room!",
            locale: 'en',
            centerVertical: true,
            closeButton: false,
            buttons: {
              confirm: {
                label: '<i class="fas fa-check"></i> Create',
                className: 'btn-light'
              }
            },
            callback: function(password){
              console.log('Password is: ' + password);
              if (password != null) {
                if (password.trim().length === 0 || password.length === 0) {
                  bootbox.alert({
                    message: "Please enter a password. Then send it to your friends.",
                    size: 'small',
                    locale: 'en',
                    centerVertical: true,
                    closeButton: false,
                    buttons: {
                      ok: {
                        className: 'btn-light'
                      }
                    },
                    callback: function () {
                      $('#create-private-room').trigger('click');
                    }
                  });
                } else {
                  $.ajax({
                    type: "POST",
                    url: '{{ url('/api') }}/createRoom',
                    data: {
                      'room-code': $('#create-room').attr('data-room'),
                      'room-name': roomName,
                      'FEN': 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1',
                      'pass': password
                    },
                    dataType: 'text'
                  }).done(function() {
                    $('#AdSenseModal').attr('data-url', $('#create-room').attr('data-url')).modal('show');
                    $('#adModalCloseBtn').attr('data-original-title', $('#AdSenseModal').attr('data-url'));
                    $('#adModalCloseBtn').tooltip();
                  });
                }
              }
            }
          });
        }
      }
    }
  });
});
function showNewRoom(newCode) {
  $.ajax({
    type: "GET",
    url: '{{ url('/api') }}/getNewRoom',
    dataType: "json"
  }).done(function(data){
    if (data.room.code != newCode) {
      var htmlContent = `
        <button id="join-room" class="btn btn-light text-dark"><i class="fas fa-sign-in-alt"></i> Play now</button>
        <button id="cancel-room" class="btn btn-light text-dark"><i class="fas fa-times"></i> Cancel</button>
      `;
      var dialog = bootbox.dialog({
        title: 'Room "' + data.room.name + '" newly created!',
        message: htmlContent,
        locale: 'en',
        size: 'small',
        centerVertical: true,
        closeButton: false
      });
      dialog.find("#join-room").click(function() {
        window.location.href = "{{ url('/') }}" + '/room/' + data.room.code;
      });

      // Handle "Cancel" button click
      dialog.find("#cancel-room").click(function() {
        dialog.modal("hide");
      });
    }
  });
}
function showLatestRoom(offset, newCode) {
  $.ajax({
    type: "POST",
    url: '{{ url('/api') }}/getLatestRoom',
    data: {
      'offset': offset
    },
    dataType: "json"
  }).done(function(data){
    if (data.room.code != newCode) {
      var htmlContent = `
        <button id="join-room" class="btn btn btn-dark text-light"><i class="fas fa-sign-in-alt"></i> Play now</button>
        <button id="cancel-room" class="btn btn btn-light text-dark"><i class="fas fa-times"></i> Cancel</button>
      `;
      var dialog = bootbox.dialog({
        title: 'Room "' + data.room.name + '" newly created!',
        message: htmlContent,
        locale: 'en',
        size: 'small',
        centerVertical: true,
        closeButton: false
      });
      dialog.find("#join-room").on('click', function() {
        if (data.color == 'white') {
          dialog.modal("hide");
          dialog.on('hidden.bs.modal', function (event) {
            $('#AdSenseModal').attr('data-url', "{{ url('/') }}" + '/room/' + data.room.code + '/white').modal('show');
            $('#adModalCloseBtn').attr('data-original-title', "{{ url('/') }}" + '/room/' + data.room.code + '/white');
            $('#adModalCloseBtn').tooltip();
          })
          // window.location.href = "{{ url('/') }}" + '/room/' + data.room.code;
        } else if (data.color == 'black') {
          dialog.modal("hide");
          dialog.on('hidden.bs.modal', function (event) {
            $('#AdSenseModal').attr('data-url', "{{ url('/') }}" + '/room/' + data.room.code + '/black').modal('show');
            $('#adModalCloseBtn').attr('data-original-title', "{{ url('/') }}" + '/room/' + data.room.code + '/black');
            $('#adModalCloseBtn').tooltip();
          })
          // window.location.href = "{{ url('/') }}" + '/room/' + data.room.code + '/guest';
        }
      });

      // Handle "Cancel" button click
      dialog.find("#cancel-room").on('mouseenter mouseleave', function(){
        // $(this).toggleClass('btn-light btn-dark');
      }).on('click', function() {
        dialog.modal("hide");
        dialog.on('hidden.bs.modal', function (event) {
          if (offset < {{ env('ROOM_OFFSET') }}) {
            showLatestRoom(offset + 1, data.room.code);
          } else if (offset == {{ env('ROOM_OFFSET') }} && !window.location.href.toLowerCase().includes('rooms')) {
            bootbox.confirm({
              message: "Go to Rooms'list!",
              size: 'small',
              locale: 'en',
              centerVertical: true,
              closeButton: false,
              buttons: {
                confirm: {
                  className: 'btn btn-dark text-light pulse-white rooms-list'
                },
                cancel: {
                  className: 'btn btn-light text-dark text-light'
                }
              },
              callback: function (result) {
                if (result == true) {
                  $('#AdSenseModal').attr('data-url', "{{ url('/rooms') }}").modal('show');
                  $('#adModalCloseBtn').attr('data-original-title', "{{ url('/rooms') }}");
                  $('#adModalCloseBtn').tooltip();
                  // window.location.href = "{{ url('/rooms') }}";
                }
              }
            });
          }
        })
      });
    }
  });
}
setTimeout(function() {
  showLatestRoom(0);
}, 2500);
$('#random-room').on('click auxclick', function(e) {
  e.preventDefault();
  $('#AdSenseModal').attr('data-url', $(this).attr('href')).modal('show');
});
$('#room-list').on('click auxclick', function(e) {
  e.preventDefault();
  $('#AdSenseModal').attr('data-url', $(this).attr('href')).modal('show');
});
$('#copy-url-white').on('click', function() {
  copyToClipboard('#url-white');
  selectText('#url-white');
  $(this).tooltip('update');
});
$('#copy-url-black').on('click', function() {
  copyToClipboard('#url-black');
  selectText('#url-black');
  $(this).tooltip('update');
});
$('#room-code').on('click', function() {
  copyToClipboard('#room-code-input');
  selectText('#room-code-input');
  $(this).find('span').tooltip('update');
});
$('#url').on('click', function() {
  copyToClipboard('#url');
  selectText('#url')
  $(this).find('span').tooltip('update');
});
const nuocCo = document.getElementById("nuoc-co");
const hetTran = document.getElementById("het-tran");

$(function () {
  $('.dropdown-toggle').dropdown();
  if (!Modernizr.touch) {
    $('#volumeSwitch').attr('title', 'Click here to switch on/off volume');
    $('#tourBtn').attr('title', 'Click here to take a website tour');
    $('[data-toggle="tooltip"]').tooltip();
    document.addEventListener('contextmenu', function(e) {
      e.preventDefault();
    });
  }
  $('.dropdown-item').each(function() {
    $(this).on('mouseenter mouseleave', function() {
      $(this).find('i').toggleClass('fad fas');
    });
  });
  let activeNavLinkSelectors = 'body.home header.site-header a.home, body.setup header.site-header a.setup, body.about header.site-header a.about, body.bmi header.site-header a.bmi, body.game header.site-header a.game, body.room header.site-header a.room, body.news header.site-header a.news, body.contact header.site-header a.contact';
  $(activeNavLinkSelectors).each(function() {
    $(this).find('i').removeClass('far').addClass('fas');
  });
  $('header.site-header ul.navbar-nav').on('mouseenter mouseleave', function() {
    $(activeNavLinkSelectors).each(function() {
      $(this).find('i').toggleClass('far fas');
    });
  });
  $('header.site-header li > a').each(function() {
    $(this).on('mouseenter mouseleave', function() {
      $(this).find('i').toggleClass('far fas');
    });
  });
});

// var is_iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
// if (is_iOS) {
//   document.addEventListener('touchstart touchend touchcancel touchmove', event => {
//     event.preventDefault();
//   }, {passive: false});
// }
document.addEventListener('touchstart touchend', function(event) {
  event.preventDefault();
});
document.oncontextmenu = function(e){
  stopEvent(e);
}
function stopEvent(event){
  if(event.preventDefault != undefined)
    event.preventDefault();
  if(event.stopPropagation != undefined)
    event.stopPropagation();
}
window.onload = () => {
  'use strict';
  if ('serviceWorker' in navigator) {
    console.log("Will the service worker register?");
    navigator.serviceWorker
    .register('{{ URL::to('/') }}/serviceWorker.js')
    .then(function(reg) {
      console.log("Yes, it did.");
    }).catch(function(err) {
      console.log("No it didn't. This happened:", err)
    });
  }
}
</script>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5e73268767defa7b"></script>