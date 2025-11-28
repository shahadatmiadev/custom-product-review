document.addEventListener('DOMContentLoaded', function () {
  const stars = document.querySelectorAll('.cpr-star-rating span');
  const input = document.getElementById('cpr_rating');

  stars.forEach(star => {
    star.addEventListener('click', () => {
      input.value = star.dataset.value;
      stars.forEach(star => {
        star.classList.remove('selected');
      });
      for (let i = 0; i < star.dataset.value; i++) {
        stars[i].classList.add('selected');
      }
    });

    star.addEventListener('mouseover', function () {
    
      for (let i = 0; i < this.getAttribute('data-value'); i++) {
        stars[i].style.color = '#ffcc00';
      }
    });
    star.addEventListener('mouseout', function(){
       for (let i = 0; i < this.getAttribute('data-value'); i++) {
        stars[i].style.color = '';
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
    const ageBtns = document.querySelectorAll('.cpr-age-range button');
    const ageInput = document.getElementById('cpr_age_range');

    ageBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        ageInput.value = btn.getAttribute('data-value');
        ageBtns.forEach(btn => {
          btn.classList.remove('selected');
        });
        btn.classList.add('selected');
      });
    })

  
});


jQuery(document).ready(function($){
    $('#cpr_file_input').on('change', function(e){
        const file = this.files[0];
        if (!file) return;

        const preview = $('#cpr_file_preview');

        if(file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e){
                preview.attr('src', e.target.result);
                preview.show();
            };
            reader.readAsDataURL(file);
        } else {
            preview.hide();
        }
    });
});
