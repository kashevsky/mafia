document.addEventListener("DOMContentLoaded", function (event) {

  Fancybox.bind("[data-fancybox]", {
    // Your custom options
  });

  const calcElAll = document.querySelectorAll('.js-calc');

  if (calcElAll.length > 0) {
    const calcRes = document.getElementById('calc-res');
    let price = 0;
    let priceFromCheck = 0;
    calcElAll.forEach(el => {
      let hidden = el.querySelector('input[type=hidden]');
      price += +hidden.value;
      calcRes.textContent = price;
      el.addEventListener('click', () => {
        priceFromCheck = 0;
        let check = document.querySelectorAll('.js-check');
        check.forEach(ch => {
          if (ch.checked) {
            priceFromCheck += +ch.value;
          }
        })
        let res = price + priceFromCheck;
        calcRes.textContent = res;
      })

    })

  }

  class Accordion {
    constructor(accordion, options) {
      this.options = options;
      this.accordion = typeof accordion === "string" ? document.querySelector(accordion) : accordion;
      if (this.accordion) {
        this._getElements();
        this._click();
        this._showPanel();
      }
    }
    _getElements() {
      this._getBtn();
      this._getPanel();
    }
    _getBtn() {
      return this.btnAll = this.accordion.querySelectorAll("[data-acc-btn]")  //получить все кнопки
    }
    _getPanel() {
      return this.panelAll = this.accordion.querySelectorAll("[data-acc-panel]")  // получить все панели
    }
    _click() {
      this.btnAll.forEach((el, id) => {
        el.addEventListener('click', () => {
          if (this.panelAll[id].style.maxHeight) {
            this.panelAll[id].style.maxHeight = null;
            this.btnAll[id].classList.remove('active');
          } else {
            this.panelAll[id].style.maxHeight = this.panelAll[id].scrollHeight + "px";
            this.btnAll[id].classList.add('active');
          }
        })
      });
    }
    _showPanel() {
      if (this.options.showPanel) {
        const counter = this.options.showPanel - 1;
        this.panelAll.forEach((p, id) => {
          if (counter === id) {
            p.style.maxHeight = this.panelAll[id].scrollHeight + "px";
            this.btnAll[id].classList.add('active');
          }
        });
      }
    }
  }

  const accordionAll = document.querySelectorAll("[data-acc]");

  if(accordionAll.length > 0){
    accordionAll.forEach(el=>{
      new Accordion(el, {
        showPanel: 1,
      });
    })



  }



  new Swiper(".swiper-portfolio", {

    slidesPerView: 1,
    spaceBetween: 20,
    rewind: true,

    navigation: {
      nextEl: ".swiper-portfolio-wrap .button-next",
      prevEl: ".swiper-portfolio-wrap .button-prev",
    },
  });


  new Swiper(".certificate-swiper", {

    loop:true,
    speed:1000,

    breakpoints: {
      0: {
        slidesPerView: 1.2,
        spaceBetween: 20,
      },

     600: {
        slidesPerView: 2,
        spaceBetween: 20,
      },

      768: {
        centeredSlides: true,
        slidesPerView: 3,
        spaceBetween: 60,
        initialSlide: 1,
      },


    },

    navigation: {
      nextEl: ".certificate-wrap .button-next",
      prevEl: ".certificate-wrap .button-prev",
    },

  });

})

