(function () {

    /*=====================================
    Sticky
    ======================================= */
    window.onscroll = function () {
        var header_navbar = document.querySelector(".navbar-area");
        var sticky = header_navbar.offsetTop;

        if (window.pageYOffset > sticky) {
            header_navbar.classList.add("sticky");
        } else {
            header_navbar.classList.remove("sticky");
        }



        // show or hide the back-top-top button
        var backToTo = document.querySelector(".scroll-top");
        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
            backToTo.style.display = "flex";
        } else {
            backToTo.style.display = "none";
        }
    };

    // section menu active
	function onScroll(event) {
		var sections = document.querySelectorAll('.page-scroll');
		var scrollPos = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;

		for (var i = 0; i < sections.length; i++) {
			var currLink = sections[i];
			var val = currLink.getAttribute('href');
			var refElement = document.querySelector(val);
			var scrollTopMinus = scrollPos + 73;
			if (refElement.offsetTop <= scrollTopMinus && (refElement.offsetTop + refElement.offsetHeight > scrollTopMinus)) {
				document.querySelector('.page-scroll').classList.remove('active');
				currLink.classList.add('active');
			} else {
				currLink.classList.remove('active');
			}
		}
	};

    document.addEventListener('DOMContentLoaded', function () {
  const track = document.querySelector('.product-track');
  const cards = Array.from(track.children);

  // Gandakan elemen untuk memastikan efek loop lancar
  track.append(...cards.map(card => card.cloneNode(true)));

  let offset = 0;
  let isPaused = false;
  const speed = 2; // kecepatan geser

  function loop() {
    if (!isPaused) {
      offset -= speed;
      // Kalau sudah geser melebihi setengah lebar total track
      if (Math.abs(offset) >= track.scrollWidth / 2) {
        offset = 0; // reset posisi tanpa patah
      }
      track.style.transform = `translateX(${offset}px)`;
    }
    requestAnimationFrame(loop);
  }

  // Pause & resume saat hover
  track.addEventListener('mouseenter', () => (isPaused = true));
  track.addEventListener('mouseleave', () => (isPaused = false));

  loop();
});


    window.document.addEventListener('scroll', onScroll);

    // for menu scroll
    var pageLink = document.querySelectorAll('.page-scroll');

    pageLink.forEach(elem => {
        elem.addEventListener('click', e => {
            e.preventDefault();
            document.querySelector(elem.getAttribute('href')).scrollIntoView({
                behavior: 'smooth',
                offsetTop: 1 - 60,
            });
        });
    });

    "use strict";

}) ();

const darkToggle = document.getElementById('darkModeToggle');
  darkToggle.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    const icon = darkToggle.querySelector('i');
    icon.classList.toggle('bi-moon');
    icon.classList.toggle('bi-sun');
  });

  // Navbar scroll effect
  window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar-nine');
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });
