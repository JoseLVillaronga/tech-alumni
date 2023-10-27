(function($){
  $(function(){

    var window_width = $(window).width();

    // convert rgb to hex value string
    function rgb2hex(rgb) {
      if (/^#[0-9A-F]{6}$/i.test(rgb)) { return rgb; }

      rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

      if (rgb === null) { return "N/A"; }

      function hex(x) {
          return ("0" + parseInt(x).toString(16)).slice(-2);
      }

      return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
    }

    $('.dynamic-color .col').each(function () {
      $(this).children().each(function () {
        var color = $(this).css('background-color'),
            classes = $(this).attr('class');
        $(this).html(rgb2hex(color) + " " + classes);
        if (classes.indexOf("darken") >= 0 || $(this).hasClass('black')) {
          $(this).css('color', 'rgba(255,255,255,.9');
        }
      });
    });

    // Floating-Fixed table of contents
    setTimeout(function() {
      var tocWrapperHeight = 260; // Max height of ads.
      var tocHeight = $('.toc-wrapper .table-of-contents').length ? $('.toc-wrapper .table-of-contents').height() : 0;
      var socialHeight = 95; // Height of unloaded social media in footer.
      var footerOffset = $('body > footer').first().length ? $('body > footer').first().offset().top : 0;
      var bottomOffset = footerOffset - socialHeight - tocHeight - tocWrapperHeight;

      if ($('nav').length) {
        $('.toc-wrapper').pushpin({
          top: $('nav').height(),
          bottom: bottomOffset
        });
      }
      else if ($('#index-banner').length) {
        $('.toc-wrapper').pushpin({
          top: $('#index-banner').height(),
          bottom: bottomOffset
        });
      }
      else {
        $('.toc-wrapper').pushpin({
          top: 0,
          bottom: bottomOffset
        });
      }
    }, 100);



    // BuySellAds Detection
    var $bsa = $(".buysellads"),
        $timesToCheck = 3;
    function checkForChanges() {
        if (!$bsa.find('#carbonads').length) {
          $timesToCheck -= 1;
          if ($timesToCheck >= 0) {
            setTimeout(checkForChanges, 500);
          }
          else {
            var donateAd = $('<div id="carbonads"><span><a class="carbon-text" href="#!" onclick="document.getElementById(\'paypal-donate\').submit();"><img src="images/donate.png" /> Help support us by turning off adblock. If you still prefer to keep adblock on for this page but still want to support us, feel free to donate. Any little bit helps.</a></form></span></div>');

            $bsa.append(donateAd);
          }
        }

    }
    checkForChanges();


    // BuySellAds Demos close button.
    $('.buysellads.buysellads-demo .close').on('click', function() {
      $(this).parent().remove();
    });


    // Github Latest Commit
    if ($('.github-commit').length) { // Checks if widget div exists (Index only)
      $.ajax({
        url: "https://api.github.com/repos/dogfalo/materialize/commits/master",
        dataType: "json",
        success: function (data) {
          var sha = data.sha,
              date = jQuery.timeago(data.commit.author.date);
          if (window_width < 1120) {
            sha = sha.substring(0,7);
          }
          $('.github-commit').find('.date').html(date);
          $('.github-commit').find('.sha').html(sha).attr('href', data.html_url);
        }
      });
    }

    // Toggle Flow Text
    var toggleFlowTextButton = $('#flow-toggle');
    toggleFlowTextButton.click( function(){
      $('#flow-text-demo').children('p').each(function(){
          $(this).toggleClass('flow-text');
        });
    });

//    Toggle Containers on page
    var toggleContainersButton = $('#container-toggle-button');
    toggleContainersButton.click(function(){
      $('body .browser-window .container, .had-container').each(function(){
        $(this).toggleClass('had-container');
        $(this).toggleClass('container');
        if ($(this).hasClass('container')) {
          toggleContainersButton.text("Turn off Containers");
        }
        else {
          toggleContainersButton.text("Turn on Containers");
        }
      });
    });

    // Detect touch screen and enable scrollbar if necessary
    function is_touch_device() {
      try {
        document.createEvent("TouchEvent");
        return true;
      } catch (e) {
        return false;
      }
    }
    if (is_touch_device()) {
      $('#nav-mobile').css({ overflow: 'auto'});
    }

    // Set checkbox on forms.html to indeterminate
    var indeterminateCheckbox = document.getElementById('indeterminate-checkbox');
    if (indeterminateCheckbox !== null)
      indeterminateCheckbox.indeterminate = true;


    // Pushpin Demo Init
    if ($('.pushpin-demo-nav').length) {
      $('.pushpin-demo-nav').each(function() {
        var $this = $(this);
        var $target = $('#' + $(this).attr('data-target'));
        $this.pushpin({
          top: $target.offset().top,
          bottom: $target.offset().top + $target.outerHeight() - $this.height()
        });
      });
    }

    // CSS Transitions Demo Init
    if ($('#scale-demo').length &&
        $('#scale-demo-trigger').length) {
      $('#scale-demo-trigger').click(function() {
        $('#scale-demo').toggleClass('scale-out');
      });
    }

    // Swipeable Tabs Demo Init
    if ($('#tabs-swipe-demo').length) {
      $('#tabs-swipe-demo').tabs({ 'swipeable': true });
    }

    // Plugin initialization
    $('.carousel.carousel-slider').carousel({fullWidth: true});
    $('.carousel').carousel();
    $('.slider').slider();
    $('.parallax').parallax();
    $('.modal').modal();
    $('.scrollspy').scrollSpy();
    $('.button-collapse').sideNav({'edge': 'left','closeOnClick': true});
    $('.datepicker').pickadate({selectYears: 20});
    $('select').not('.disabled').material_select();
    $('input.autocomplete').autocomplete({
      data: {"Apple": null, "Microsoft": null, "Google": 'http://placehold.it/250x250'}
    });

    // Chips
    $('.chips').material_chip();
    $('.chips-initial').material_chip({
      readOnly: true,
      data: [{
        tag: 'Apple',
      }, {
        tag: 'Microsoft',
      }, {
        tag: 'Google',
      }]
    });
    $('.chips-placeholder').material_chip({
      placeholder: 'Enter a tag',
      secondaryPlaceholder: '+Tag',
    });
    $('.chips-autocomplete').material_chip({
      autocompleteData: {
        'Apple': null,
        'Microsoft': null,
        'Google': null
      }
    });


  }); // end of document ready
})(jQuery); // end of jQuery name space
function openMenu(){
  $('.button-collapse').sideNav('show');
}
function modificar(){
  if(window.innerWidth < 601){
    var h=window.innerHeight - 412;
  }else{
    var h=window.innerHeight - 312;
  }
  document.getElementById('content').style.minHeight=h+"px";
}
var cmData = function(ip,mac){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","/cm-data.php?ip="+ip);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById(mac);
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var cmLogs = function(ip,mac){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","/cm-log.php?ip="+ip);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById(mac);
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var cmDataClose = function(mac){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","/vacio.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById(mac);
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var pruebaInfo = function(mac,id){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","/tests-info.php?id="+mac+"&mac="+id);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById(mac);
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var pruebaTipo = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","/test-lista-abiertas.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("prueba");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var pruebaTipo2 = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","/test-lista-cerradas.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("prueba");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var pruebaTipo3 = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","/test-lista-todas.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("prueba");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var pruebaCerrar = function(mac){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","/vacio.php");
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById(mac);
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
var monitor = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","monitor-din.php?nc="+Math.random());
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("monitor");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
setInterval(monitor,4000);
var monitorUD = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","monitorUD-din.php?nc="+Math.random());
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("monitorUD");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
setInterval(monitorUD,4000);
var virtOper = function(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","virt-estado2.php?nc="+Math.random());
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				var caja = document.getElementById("virtOper");
				caja.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send(null);
}
setInterval(virtOper,6000);
var modificar = function(){
	var h=window.innerHeight - 284;
	var i=window.innerHeight - 354;
	document.getElementById('content').style.minHeight=h+"px";
	document.getElementById('content2').style.minHeight=i+"px";
}