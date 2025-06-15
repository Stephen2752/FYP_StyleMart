<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>StyleMart - Categories</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      margin: 0;
    }
    .header {
      background: #333;
      color: white;
      padding: 15px 20px;
      text-align: center;
      font-size: 24px;
    }
    .category-menu {
      display: flex;
      justify-content: center;
      background: #fff;
      padding: 15px;
      gap: 40px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .dropdown {
      position: relative;
    }
    .dropdown button {
      background: none;
      border: none;
      font-size: 18px;
      cursor: pointer;
      padding: 8px 12px;
    }
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #fff;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      padding: 10px;
      z-index: 1;
      border-radius: 5px;
    }
    .dropdown-content a {
      display: block;
      padding: 8px 12px;
      text-decoration: none;
      color: #333;
    }
    .dropdown-content a:hover {
      background-color: #f0f0f0;
    }
    .dropdown:hover .dropdown-content {
      display: block;
    }
  </style>
</head>
<body>
  <div class="header">StyleMart - Categories</div>

  <nav class="category-menu">
    <?php
    $categories = ['Mens', 'Womens', 'Kids'];
    $subcategories = ['Clothes', 'Pants', 'Shoes'];

    foreach ($categories as $category) {
      echo '<div class="dropdown">';
      echo "<button>$category</button>";
      echo '<div class="dropdown-content">';
      foreach ($subcategories as $sub) {
        $lowerCategory = strtolower($category);
        $lowerSub = strtolower($sub);
        echo "<a href='category.php?type=$lowerCategory&item=$lowerSub'>$sub</a>";
      }
      echo '</div></div>';
    }
    ?>
  </nav>
</body>
</html>
