<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Test Cart</title>
  <link rel="stylesheet" href="cart.css" />
</head>
<body>

<div id="payment-modal">
  <button onclick="document.getElementById('payment-modal').style.display='none'">&#10006;</button>
  <img src="example.jpg" alt="Example">
  <form>
    <select id="shipping-address-dropdown">
      <option value="1">Test Address</option>
    </select>
    <input type="file" />
    <button type="submit">Submit</button>
  </form>
</div>

</body>
</html>
