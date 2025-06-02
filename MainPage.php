
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>StyleMart</title>
  <link rel="stylesheet" href="MainPage.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
</head>
<body>
     <div class="page-wrapper">
  <!-- Topbar -->
  <header class="topbar">
    <div class="logo">StyleMart</div>
<!-- From Uiverse.io by joe-watson-sbf --> 
    <div class="search">
        <input type="text" class="search__input" placeholder="Type your text">
        <button class="search__button">
            <svg class="search__icon" aria-hidden="true" viewBox="0 0 24 24">
                <g>
                    <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                </g>
            </svg>
        </button>
    </div>
    <div class="icons">
      <span class="icon">👤</span>
      <span class="icon">🛒</span>
    </div>
  </header>

  <!-- Menu bar -->
  <nav class="menu-bar">
    <div class="dropdown">
      <button>☰ Category</button>
      <div class="dropdown-content">
        <a href="#">Men</a>
        <a href="#">Women</a>
        <a href="#">Kids</a>
        <a href="#">Sport</a>
      </div>
    </div>
    <button>All Products</button>
    <button>🛒 Cart</button>
    <button>❤️ Favorite</button>
  </nav>
  

  <!-- Products Grid -->
<section class="products">
  <div class="grid">
    <?php include('load_product.php'); ?>
  </div>
</section>
     </div>

<!-- Footer -->
  <footer class="footer">
    <div class="footer-section about">
      <h4>About us</h4>
      <p>Style Mart is your go-to destination for trendy, affordable fashion. We offer a wide selection of styles, brands, and sizes—so you can find the perfect look without breaking the bank. New arrivals drop regularly, keeping your wardrobe fresh and fabulous.</p>
    </div>
    <div class="footer-section contact">
      <h4>Contact</h4>
      <p>012-121 2753 (Stephen)<br>012-123 6251 (Wen Hin)<br>012-112 2367 (Mun Kit)</p>
    </div>
    <div class="footer-section address">
      <h4>Address</h4>
      <p>Persiaran Multimedia, 63100<br>Cyberjaya, Selangor</p>
    </div>
    <div class="footer-section connect">
      <h4>Connect With Us</h4>
      <!-- 这里我们用一个额外的容器来放 logo -->
      <div class="social-icons">
        <a href="https://www.facebook.com" target="_blank">
          <img src="image/fb.png" alt="Facebook">
        </a>
        <a href="https://www.instagram.com" target="_blank">
          <img src="image/ig.png" alt="Instagram">
        </a>
        <a href="https://www.x.com" target="_blank">
          <img src="image/twitter.png" alt="x">
        </a>
      </div>
    </div>
      
  </footer>
</body>
</html>
