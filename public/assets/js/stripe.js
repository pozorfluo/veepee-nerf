(function () {
  "use strict";

  //----------------------------------------------------------------- main ---
  window.addEventListener("DOMContentLoaded", function (event) {
    // A reference to Stripe.js initialized with your real test publishable API key.
    const stripe = Stripe(
      "pk_test_51GxBXtBaAL5MTpCuXDqjccAhtRYfVzeTVPdgxM6EHIMeYypX8Z1op6fLfDYOVA6Q4KvU8U1jD1J6eNdA1GLlC5Yh00Fs3BqlVy",
      { locale: "fr" }
    );
    const stripe_elements = stripe.elements();

    // Piggy-back on server created PaymentIntent.
    const stripe_button = document.querySelector("#payment-form button");
    const client_secret = stripe_button.dataset.secret;

    // Disable the button until we have Stripe set up on the page.
    stripe_button.disabled = true;
    stripe_button.classList.add("btn");

    const style = {
      base: {
        color: "#32325d",
        fontFamily: "Arial, sans-serif",
        fontSmoothing: "antialiased",
        fontSize: "16px",
        "::placeholder": {
          color: "#32325d",
        },
      },
      invalid: {
        fontFamily: "Arial, sans-serif",
        color: "#fa755a",
        iconColor: "#fa755a",
      },
    };

    // Stripe injects an iframe into the DOM
    const card = stripe_elements.create("card", { style: style });
    card.mount("#card-element");
    card.on("change", function (event) {
      // Disable the Pay button if there are no card details in the Element
      document.querySelector("button").disabled = event.empty;
      document.querySelector("#card-errors").textContent = event.error
        ? event.error.message
        : "";
    });

    const form = document.getElementById("payment-form");
    form.addEventListener("submit", function (event) {
      console.log("payWithCard !");
      event.preventDefault();
      // Complete payment when the submit button is clicked
      payWithCard(stripe, card, client_secret);
    });

    // Calls stripe.confirmCardPayment
    // If the card requires authentication Stripe shows a pop-up modal to
    // prompt the user to enter authentication details without leaving your page.
    const payWithCard = function (stripe, card, clientSecret) {
      loading(true);
      stripe
        .confirmCardPayment(clientSecret, {
          payment_method: {
            card: card,
          },
        })
        .then(function (result) {
          if (result.error) {
            // Show error to your customer
            showError(result.error.message);
          } else {
            // The payment succeeded!
            orderComplete(result.paymentIntent.id);
          }
        });
    };

    /* ------- UI helpers ------- */

    // Shows a success message when the payment is complete
    const orderComplete = function (paymentIntentId) {
      loading(false);
      document
        .querySelector(".result-message a")
        .setAttribute(
          "href",
          "https://dashboard.stripe.com/test/payments/" + paymentIntentId
        );
      document.querySelector(".result-message").classList.remove("hidden");
      document.querySelector("button").disabled = true;
    };

    // Show the customer the error from Stripe if their card fails to charge
    const showError = function (errorMsgText) {
      loading(false);
      const errorMsg = document.querySelector("#card-errors");
      errorMsg.textContent = errorMsgText;
      setTimeout(function () {
        errorMsg.textContent = "";
      }, 4000);
    };

    // Show a spinner on payment submission
    const loading = function (isLoading) {
      if (isLoading) {
        // Disable the button and show a spinner
        document.querySelector("button").disabled = true;
        document.querySelector("#spinner").classList.remove("hidden");
        document.querySelector("#button-text").classList.add("hidden");
      } else {
        document.querySelector("button").disabled = false;
        document.querySelector("#spinner").classList.add("hidden");
        document.querySelector("#button-text").classList.remove("hidden");
      }
    };
  }); /* DOMContentLoaded */
})(); /* IIFE */
