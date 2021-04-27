<html>
  <head>
  <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script src="https://cdn.payvision.com/checkout/1.0.1/checkout-library.js"></script>
    <!-- your head definitions -->
  </head>
  <body>
    <!-- your own html code -->
    <div id="checkout-container"></div>
    <!-- your own html code -->
  </body>
  <script>
    const options = {
  live: true,
};
/*
styles: {
    imageUrl: '',
    autoHeight: false,
    generalStyles: {
      backgroundColor: '#f6f7fb',
      fontFamily: 'Source Sans Pro',
      fontFamilyUrl: 'Font family url',
      primaryThemeColor: '#213d8f',
      accentThemeColor: '#909ec7',
      secondaryThemeColor: '#909ec7',
      infoThemeColor: '#1db9de',
      warningThemeColor: '#fec61f',
      errorThemeColor: '#e6493a',
      successThemeColor: '#35ba6d',
    },
    formStyles: {
      backgroundFormColor: '#ffffff',
      backgroundFormContainerColor: '#ffffff',
      fontSize: '14px',
      borderWidth: '1px',
      borderRadius: '2px',
    },
    paymentButtonStyles: {
      fontSize: '16px',
      borderWidth: '1px',
      borderRadius: '2px',
      fontColor: '#ffffff',
      backgroundColor: '#8ec03f',
      borderColor: '#8ec03f',
    },
    iframe: {
      width: '100%',
      height: '780px',
    },
  },*/
    const checkout = new Checkout(
      "<?php echo $checkoutId; ?>",
      "checkout-container",
      options
    )
    checkout.render()
  </script>
</html>
