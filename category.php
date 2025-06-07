<?php
// category_form.php

// Define categories and subcategories
$categories = [
    "Men" => ["Jeans", "Shirt", "Shoes"],
    "Women" => ["Jeans", "Shirt", "Shoes"],
    "Kids" => ["Jeans", "Shirt", "Shoes"],
    "Sport" => ["Jeans", "Shirt", "Shoes"],
];

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $main_category = $_POST['main_category'] ?? '';
    $sub_category = $_POST['sub_category'] ?? '';

    if ($main_category && $sub_category && isset($categories[$main_category]) && in_array($sub_category, $categories[$main_category])) {
        // Here you can add your logic to save the category selection to database or session
        $message = "You selected: <strong>$main_category - $sub_category</strong>";
    } else {
        $message = "Invalid category selection.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Category Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 30px;
    }
    label, select {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
    }
    select {
      width: 200px;
      padding: 5px;
    }
    .message {
      margin: 20px 0;
      font-size: 1.1em;
      color: green;
    }
  </style>
  <script>
    // JS to update subcategory options dynamically
    const categories = <?php echo json_encode($categories); ?>;

    function updateSubCategories() {
      const mainSelect = document.getElementById('main_category');
      const subSelect = document.getElementById('sub_category');
      const selectedMain = mainSelect.value;

      // Clear current options
      subSelect.innerHTML = '<option value="">-- Select Sub Category --</option>';

      if (selectedMain && categories[selectedMain]) {
        categories[selectedMain].forEach(sub => {
          const option = document.createElement('option');
          option.value = sub;
          option.textContent = sub;
          subSelect.appendChild(option);
        });
      }
    }

    window.addEventListener('DOMContentLoaded', () => {
      document.getElementById('main_category').addEventListener('change', updateSubCategories);
    });
  </script>
</head>
<body>
  <h1>Select Product Category</h1>

  <?php if ($message): ?>
    <div class="message"><?php echo $message; ?></div>
  <?php endif; ?>

  <form id="categoryForm" action="" method="POST">
    <label for="main_category">Main Category:</label>
    <select name="main_category" id="main_category" required>
      <option value="">-- Select Main Category --</option>
      <?php foreach ($categories as $main => $subs): ?>
        <option value="<?php echo htmlspecialchars($main); ?>" <?php if (isset($main_category) && $main_category === $main) echo 'selected'; ?>>
          <?php echo htmlspecialchars($main); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label for="sub_category">Sub Category:</label>
    <select name="sub_category" id="sub_category" required>
      <option value="">-- Select Sub Category --</option>
      <?php
      // If form submitted and main category selected, populate subcategories server-side on reload
      if (!empty($main_category) && isset($categories[$main_category])) {
          foreach ($categories[$main_category] as $sub) {
              $selected = (isset($sub_category) && $sub_category === $sub) ? 'selected' : '';
              echo "<option value=\"" . htmlspecialchars($sub) . "\" $selected>" . htmlspecialchars($sub) . "</option>";
          }
      }
      ?>
    </select>

    <br />
    <button type="submit">Submit Category</button>
  </form>
</body>
</html>
