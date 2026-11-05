

export function initPayment() {
  return {
    showModalPaymentBox: false,
    selectedUser: null,
    selectedPayment: null,
    selectedPrice: null,

    init() { },

    savePayment(event) {
      event.preventDefault();
      const inputs = event.target.querySelectorAll('input[type="radio"]');
      let users = [];
      inputs.forEach((input) => {
        if (input.checked) {
          users.push({
            status: input.value,
            name: input.name,
            id: input.dataset.id
          });
        }
      });
      console.log(users);

      this.$store.globalData._sendAjax('#', {
        users,
      }).then(response => response.json()).then(data => {
        console.log(data);

      }).catch(error => {
        console.error(error);
      });




    },

    savePaymentBox(){
      console.log(this.selectedPrice, this.selectedPayment);
      this.selectedUser.payment = [...this.selectedUser.payment, {
        status: this.selectedPayment,
        amount: this.selectedPrice
      }]      
      this.showModalPaymentBox = false
    }

  }
}


export function initUserPayment() {
  return {

    init() { },

  }
}

export function initPaymentBox() {
  return {

    init() { },

    deletePayment(user, obj) {
      console.log(user);
      user.payment = user.payment.filter(item => item.pay !== obj.pay);
    }


  }
}







