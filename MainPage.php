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

.icons .icon {
margin-left: 15px;
font-size: 20px;
cursor: pointer;
}


/* From Uiverse.io by joe-watson-sbf */ 
/* --- Search Results Styles --- */
.search-wrapper {
position: relative;
flex: 1;
margin: 0 20px;
max-width: 500px;
}

.search {
display: flex;
align-items: center;
width: 100%;
}

.search__input {
font-size: 1rem;
font-family: inherit;
background-color: #f4f2f2;
border: none;
color: #646464;
padding: 0.7rem 1rem;
border-radius: 30px;
width: 100%;
transition: all ease-in-out 0.5s;
}

.search__input:hover,
.search__input:focus {
box-shadow: 0 0 1em #00000013;
background-color: #f0eeee;
outline: none;
}

.search__input::placeholder {
font-weight: 100;
color: #ccc;
}

.search__button {
border: none;
background-color: #f4f2f2;
margin-left: -3rem;
z-index: 1;
}

.search__icon {
height: 1.3em;
width: 1.3em;
fill: #b4b4b4;
}

/* Search results (dropdown) */
.search-results {
position: absolute;
top: 100%;
left: 0;
width: 100%;
background: rgb(95, 95, 95);
color: white;
border: 1px solid #3e3e3e;
border-radius: 5px;
margin-top: 6px;
z-index: 999;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
font-size: 0.95rem;
}

.search-result-item {
padding: 10px;
border-bottom: 1px solid #5a5a5a;
}

.search-results a,
.search-result-item a {
color: white !important;
text-decoration: none;
display: block;
}

.search-result-item a:hover {
color: #ddd;
text-decoration: underline;
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
background: rgb(255, 255, 255);
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

@media (max-width: 768px) {
  body {
    overflow-x: hidden;
  }

.topbar {
  background: #3e3e3e;
  color: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 12px;
  gap: 6px;
  flex-wrap: nowrap;
}

.topbar .logo {
  font-size: 18px;
  font-weight: bold;
  white-space: nowrap;
  flex-shrink: 0;
}

.search-wrapper {
  position: relative;
  flex: 1;
  margin: 0 10px;
  max-width: 100%;
}


.icons {
  display: flex;
  gap: 2px;  /* 原本是 4px，现在只留 2px */
  font-size: 18px;
  white-space: nowrap;
  flex-shrink: 0;
  margin-left: 4px;
}



.search-results {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  background: #5f5f5f;
  color: white;
  z-index: 9999;  /* VERY important */
  border-radius: 5px;
  margin-top: 6px;
  box-shadow: 0 0 10px rgba(0,0,0,0.2);
  max-height: 300px;
  overflow-y: auto;
}




/* --- Menu Bar --- */
.menu-bar {
  background: white;
  display: flex;
  justify-content: center;
  padding: 8px 0;
  gap: 20px;
  border-bottom: 1px solid #ddd;
}

.menu-bar .dropdown {
  position: relative;
}

.menu-bar button {
  background: none;
  border: none;
  font-size: 15px;
  cursor: pointer;
  padding: 5px 10px;
}

/* Dropdown content: fixed */
.dropdown-content {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  background-color: white;
  min-width: 120px;
  border-radius: 4px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  z-index: 9999;
}

.dropdown-content a {
  display: block;
  padding: 8px 12px;
  text-decoration: none;
  color: #333;
  font-size: 14px;
}

.dropdown-content a:hover {
  background-color: #f2f2f2;
}

.dropdown:hover .dropdown-content {
  display: block;
}

  .grid {
    grid-template-columns: repeat(2, 1fr);
    padding: 0 10px;
    gap: 20px;
  }

  .product-card {
    width: 100%;
  }

  .footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }

  .footer-section {
    max-width: 100%;
    flex: 1 1 100%;
  }
}

  </style> 
</head>
<body>
  <div class="page-wrapper">
    
  <header class="topbar">
  <!-- Left: Logo -->
  <div class="logo">StyleMart</div>

  <!-- Center: Search Bar -->
  <div class="search-wrapper">
    <div class="search">
      <input type="text" id="searchInput" class="search__input" placeholder="Type your text" oninput="searchProduct(this.value)">
      <button class="search__button">
        <svg class="search__icon" viewBox="0 0 24 24">
          <g>
            <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"/>
          </g>
        </svg>
      </button>
    </div>
    <div id="searchResults" class="search-results"></div>
  </div>

<!-- JS for live search -->
<script>
function searchProduct(keyword) {
  if (keyword.length === 0) {
    document.getElementById("searchResults").innerHTML = "";
    return;
  }

  fetch("search.php?query=" + encodeURIComponent(keyword))
    .then(response => response.text())
    .then(data => {
      document.getElementById("searchResults").innerHTML = data;
    });
}
</script>



      <!-- Right: Icons -->
   <div class="icons">
    <span class="icon" onclick="checkLogin('profile.php')">👤</span>
    <span class="icon" onclick="checkLogin('cart.php')">🛒</span>
    <span class="icon" onclick="checkLogin('favorite.php')">❤️</span>
  </div>
</header>

    <!-- Menu bar -->
    <!-- Menu bar -->
<nav class="menu-bar">
  <div class="dropdown">
    <button>Mens</button>
    <div class="dropdown-content">
      <a href="category_men_shirt.php">Clothes</a>
      <a href="category_men_pants.php">Pants</a>
      <a href="category_men_shoes.php">Shoes</a>
    </div>
  </div>

  <div class="dropdown">
    <button>Womens</button>
    <div class="dropdown-content">
      <a href="category_women_shirt.php">Clothes</a>
      <a href="category_women_pants.php">Pants</a>
      <a href="category_women_shoes.php">Shoes</a>
    </div>
  </div>

  <div class="dropdown">
    <button>Kids</button>
    <div class="dropdown-content">
      <a href="category_kids_shirt.php">Clothes</a>
      <a href="category_kids_pants.php">Pants</a>
      <a href="category_kids_shoes.php">Shoes</a>
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
      <a href="https://www.facebook.com" target="_blank"><img src="uploads/fb.png" alt="Facebook"></a>
      <a href="https://www.instagram.com" target="_blank"><img src="uploads/ig.png" alt="Instagram"></a>
      <a href="https://www.x.com" target="_blank"><img src="uploads/twitter.png" alt="X"></a>
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