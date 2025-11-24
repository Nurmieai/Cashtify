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
  const speed = 1; // kecepatan geser

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


  document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById('photoInput');
  const modalEl = document.getElementById('cropperModal');
  const imageEl = document.getElementById('imageToCrop');
  const previewEl = document.getElementById('profilePreview');
  const cropBtn = document.getElementById('cropImageBtn');
  let cropper;

  const modal = new bootstrap.Modal(modalEl);

  input?.addEventListener('change', e => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
      imageEl.src = ev.target.result;
      modal.show();
    };
    reader.readAsDataURL(file);
  });

  modalEl?.addEventListener('shown.bs.modal', () => {
    cropper = new Cropper(imageEl, {
      aspectRatio: 1,
      viewMode: 1,
      background: false,
    });
  });

  modalEl?.addEventListener('hidden.bs.modal', () => {
    cropper?.destroy();
    cropper = null;
  });

  cropBtn?.addEventListener('click', () => {
    const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
    previewEl.src = canvas.toDataURL();
    modal.hide();

    const croppedInput = document.createElement('input');
    croppedInput.type = 'hidden';
    croppedInput.name = 'cropped_image';
    croppedInput.value = canvas.toDataURL('image/png');
    document.querySelector('form').appendChild(croppedInput);
  });
});

document.addEventListener("DOMContentLoaded", function() {
  const input = document.getElementById("photo");
  const preview = document.getElementById("previewImage");

  if (input && preview) {
    input.addEventListener("change", () => {
      const file = input.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
    const errorMessages = window.errorMessages || null;
    const successMessage = window.successMessage || null;

    if (errorMessages) {
        Swal.fire({
            icon: 'error',
            title: 'Ups!',
            html: errorMessages.join('<br>'),
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Oke',
        });
    }

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successMessage,
            confirmButtonColor: '#dc3545',
        });
    }
});

