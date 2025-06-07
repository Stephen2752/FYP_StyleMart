<?php
session_start(); // ✅ 所有输出之前执行
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>StyleMart</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
<style>
body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background: #f2f2f2;
    color: #333;
    display: flex;
   flex-direction: column;
   min-height: 100vh; /* full viewport height */
   margin: 0;
  }
  
  .page-wrapper {
  flex: 1; /* grow and fill vertical space pushing footer down */
  display: flex;
  flex-direction: column;
}
  
  /* Topbar */
  .topbar {
    background: #3e3e3e;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
  }
  
  .topbar .logo {
    font-size: 20px;
    font-weight: bold;
  }
  
  /* From Uiverse.io by joe-watson-sbf */ 
  .search {
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-align: center;
  }

  .search__input {
    font-family: inherit;
    font-size: inherit;
    background-color: #f4f2f2;
    border: none;
    color: #646464;
    padding: 0.7rem 1rem;
    border-radius: 30px;
    width: 30em;
    transition: all ease-in-out .5s;
    margin-right: -2rem;
  }

  .search__input:hover, .search__input:focus {
    box-shadow: 0 0 1em #00000013;
  }

  .search__input:focus {
    outline: none;
    background-color: #f0eeee;
  }

  .search__input::-webkit-input-placeholder {
    font-weight: 100;
    color: #ccc;
  }

  .search__input:focus + .search__button {
    background-color: #f0eeee;
  }

  .search__button {
    border: none;
    background-color: #f4f2f2;
    margin-top: .1em;
  }

  .search__button:hover {
    cursor: pointer;
  }

  .search__icon {
    height: 1.3em;
    width: 1.3em;
    fill: #b4b4b4;
  }
  
  .icons .icon {
    margin-left: 15px;
    font-size: 20px;
    cursor: pointer;
  }
  
  /* Menu bar */
  .menu-bar {
    background: white;
    display: flex;
    justify-content: center;
    padding: 12px 0;
    gap: 30px;
    border-bottom: 1px #ddd;
  }
  
  .menu-bar button {
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
  }
  
      /* 下拉容器 */
  .dropdown {
    position: relative;
    display: inline-block;
  }

  .dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 120px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    z-index: 1;
    top: 100%;
    left: 0;
    border-radius: 4px;
    /* 移除 margin-top */
  }

  .dropdown-content a {
    color: #333;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
  }

  .dropdown-content a:hover {
    background-color: #f2f2f2;
  }

  .dropdown:hover .dropdown-content {
    display: block;
  }


    /* Product Grid */
  /* 产品卡片整体样式 */
  .product-card {
    background: white;
    border: 1px #ccc;
    border-radius: 8px;
    text-align: center;
    padding: 8px;
    transition: box-shadow 0.3s;
    width: 160px; /* 稍微更窄 */
    box-sizing: border-box;
    display: block;
    text-decoration: none;
    color: inherit;

  }

  .product-card img {
    width: 100%;
    height: 160px; /* 高度更小 */
    object-fit: cover; /* 图片保持比例填满 */
    border-radius: 4px;
  }

  .product-card p {
    margin: 3px 0;
    font-size: 13px; /* 字体更小 */
    line-height: 1.2;
  }

  .product-card .price {
    font-weight: bold;
    color: #6a5acd;
    font-size: 13px;
    margin-top: 3px;
  }

  .product-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  }


  /* 产品网格布局调整 */
  .grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr); /* 一行5个产品 */
    gap: 30px; /* 产品卡片间距 */
    justify-items: center;
    margin-top: 20px;
  }

    
    /* Footer */
  /* Footer - 让 footer 不覆盖商品内容，内部间距缩小 */
  .footer {
    background: #747474;
    color: #fff;
    display: flex;
    justify-content: space-around;
    padding: 10px 20px;
    flex-wrap: wrap;
    text-align: left;
    font-size: 14px;
    line-height: 1.4;
    position: relative; /* 改为相对定位，而非固定 */
    bottom: auto;
    left: auto;
    width: 100%;
    box-sizing: border-box;
    margin-top: 20px; /* 增加上方间距，防止与商品重叠 */
  }

  .footer-section {
    flex: 1 1 250px; /* 把每个section的最小宽度提高到250px */
    max-width: 250px; /* 让文字一行容纳更多 */
    margin: 5px 10px;
  }



  .footer h4 {
    margin-bottom: 5px; /* 缩小标题和文字之间的距离 */
    color: black;
  }

  .footer p {
    margin: 2px 0; /* 缩小段落的上下 margin */
  }

  .footer-section.connect .social-icons {
    display: flex;
    gap: 8px; /* 缩小 social icon 间距 */
    margin-top: 5px;
  }

  .footer-section.connect .social-icons img {
    width: 28px; /* 保持图标尺寸 */
    height: 28px;
  }
</style>
</head>
<body>
  <div class="page-wrapper">
    <!-- Topbar -->
    <header class="topbar">
      <div class="logo">StyleMart</div>

      <!-- Search -->
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

      <!-- Icons -->
      <div class="icons">
        <span class="icon" id="profile">👤</span>
        <span class="icon" onclick="checkLogin('cart.php')">🛒</span>
        <span class="icon" onclick="checkLogin('favorite.php')">❤️</span>
      </div>
    </header>

    <!-- Menu bar -->
    <nav class="menu-bar">
      <div class="dropdown">
        <button>Mens</button>
        <div class="dropdown-content">
          <a href="category_men_clothes.html">Clothes</a>
          <a href="category_men_shirts.html">Shirts</a>
          <a href="category_men_shoes.html">Shoes</a>
        </div>
      </div>
      <div class="dropdown">
        <button>Womens</button>
        <div class="dropdown-content">
          <a href="category_women_clothes.html">Clothes</a>
          <a href="category_women_shirts.html">Shirts</a>
          <a href="category_women_shoes.html">Shoes</a>
        </div>
      </div>
      <div class="dropdown">
        <button>Kids</button>
        <div class="dropdown-content">
          <a href="category_kid_clothes.html">Clothes</a>
          <a href="category_kid_shirts.html">Shirt</a>
          <a href="category_kid_shoes.html">Shoes</a>
        </div>
      </div>
      <div class="dropdown">
        <button>Sports</button>
        <div class="dropdown-content">
          <a href="#">?</a>
          <a href="#">?</a>
          <a href="#">?</a>
        </div>
      </div>
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
      <div class="social-icons">
        <a href="https://www.facebook.com" target="_blank">
          <img src="uploads/fb.png" alt="Facebook">
        </a>
        <a href="https://www.instagram.com" target="_blank">
          <img src="uploads/ig.png" alt="Instagram">
        </a>
        <a href="https://www.x.com" target="_blank">
          <img src="uploads/twitter.png" alt="x">
        </a>
      </div>
    </div>
  </footer>

  <!-- Profile login check script -->
  <script>
    document.getElementById("profile").addEventListener("click", function () {
      fetch("check_login.php")
        .then((res) => res.json())
        .then((data) => {
          if (data.loggedIn) {
            window.location.href = "profile.php";
          } else {
            window.location.href = "login.html";
          }
        })
        .catch((err) => {
          console.error("Error checking login status:", err);
          window.location.href = "login.html"; // fallback if error
        });
    });
     function checkLogin(redirectUrl) {
    fetch("check_login.php")
      .then((res) => res.json())
      .then((data) => {
        if (data.loggedIn) {
          window.location.href = redirectUrl;
        } else {
          alert("Please log in to use this feature.");
          window.location.href = "login.html";
        }
      })
      .catch((err) => {
        console.error("Login check failed:", err);
        window.location.href = "login.html";
      });
  }
  </script>
</body>
</html>