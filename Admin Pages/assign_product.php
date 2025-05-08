<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inventory Categories with Assign Buttons</title>
  <style>
    body {
      font-family: sans-serif;
      padding: 20px;
    }

    h2 {
      margin-top: 40px;
      font-size: 22px;
      color: #333;
    }

    .box-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
      margin-top: 15px;
    }

    .product-box {
      height: 130px;
      background-color: #f4f4f4;
      border: 2px solid #ccc;
      border-radius: 8px;
      text-align: center;
      padding: 10px;
      font-size: 14px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .assign-btn {
      margin-top: 5px;
      padding: 5px 10px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .assign-btn:hover {
      background-color: #0056b3;
    }

    .product-select {
      margin-top: 10px;
    }
  </style>
</head>
<body>

<!-- Category Sections -->

<h2>All Products</h2>
<div class="box-grid">
     <!-- Product Box  -->
  <div class="product-box" id="all1">
    <div class="product-content" id="allcontent0">Empty</div>
    <button class="assign-btn" onclick="assignProduct('allcontent0')">Assign</button>
  </div>
  <!-- Product Box 2 -->
  <div class="product-box" id="all1">
    <div class="product-content" id="allcontent1">Empty</div>
    <button class="assign-btn" onclick="assignProduct('allcontent1')">Assign</button>
  </div>
  <!-- Product Box 3 -->
  <div class="product-box" id="all2">
    <div class="product-content" id="allcontent2">Empty</div>
    <button class="assign-btn" onclick="assignProduct('allcontent2')">Assign</button>
  </div>
  <!-- Product Box 4 -->
  <div class="product-box" id="all3">
    <div class="product-content" id="allcontent3">Empty</div>
    <button class="assign-btn" onclick="assignProduct('allcontent3')">Assign</button>
  </div>
  <!-- Product Box 5 -->
  <div class="product-box" id="all4">
    <div class="product-content" id="allcontent4">Empty</div>
    <button class="assign-btn" onclick="assignProduct('allcontent4')">Assign</button>
  </div>
  <!-- Product Box 6 -->
  <div class="product-box" id="all5">
    <div class="product-content" id="allcontent5">Empty</div>
    <button class="assign-btn" onclick="assignProduct('allcontent5')">Assign</button>
  </div>
  <!-- Product Box 7 -->
  <div class="product-box" id="all6">
    <div class="product-content" id="allcontent6">Empty</div>
    <button class="assign-btn" onclick="assignProduct('allcontent6')">Assign</button>
  </div>
  <!-- Product Box 8 -->
  <div class="product-box" id="all7">
    <div class="product-content" id="allcontent7">Empty</div>
    <button class="assign-btn" onclick="assignProduct('allcontent7')">Assign</button>
  </div>
</div>

<h2>Trending</h2>
<div class="box-grid">
  <!-- Product Box 1 -->
  <div class="product-box" id="trend0">
    <div class="product-content" id="trendcontent0">Empty</div>
    <button class="assign-btn" onclick="assignProduct('trendcontent0')">Assign</button>
  </div>
  <!-- Product Box 2 -->
  <div class="product-box" id="trend1">
    <div class="product-content" id="trendcontent1">Empty</div>
    <button class="assign-btn" onclick="assignProduct('trendcontent1')">Assign</button>
  </div>
  <!-- Product Box 3 -->
  <div class="product-box" id="trend2">
    <div class="product-content" id="trendcontent2">Empty</div>
    <button class="assign-btn" onclick="assignProduct('trendcontent2')">Assign</button>
  </div>
  <!-- Product Box 4 -->
  <div class="product-box" id="trend3">
    <div class="product-content" id="trendcontent3">Empty</div>
    <button class="assign-btn" onclick="assignProduct('trendcontent3')">Assign</button>
  </div>
  <!-- Product Box 5 -->
  <div class="product-box" id="trend4">
    <div class="product-content" id="trendcontent4">Empty</div>
    <button class="assign-btn" onclick="assignProduct('trendcontent4')">Assign</button>
  </div>
  <!-- Product Box 6 -->
  <div class="product-box" id="trend5">
    <div class="product-content" id="trendcontent5">Empty</div>
    <button class="assign-btn" onclick="assignProduct('trendcontent5')">Assign</button>
  </div>
  <!-- Product Box 7 -->
  <div class="product-box" id="trend6">
    <div class="product-content" id="trendcontent6">Empty</div>
    <button class="assign-btn" onclick="assignProduct('trendcontent6')">Assign</button>
  </div>
  <!-- Product Box 8 -->
  <div class="product-box" id="trend7">
    <div class="product-content" id="trendcontent7">Empty</div>
    <button class="assign-btn" onclick="assignProduct('trendcontent7')">Assign</button>
  </div>
</div>

<h2>Men</h2>
<div class="box-grid">
  <!-- Product Box 1 -->
  <div class="product-box" id="men0">
    <div class="product-content" id="mencontent0">Empty</div>
    <button class="assign-btn" onclick="assignProduct('mencontent0')">Assign</button>
  </div>
  <!-- Product Box 2 -->
  <div class="product-box" id="men1">
    <div class="product-content" id="mencontent1">Empty</div>
    <button class="assign-btn" onclick="assignProduct('mencontent1')">Assign</button>
  </div>
  <!-- Product Box 3 -->
  <div class="product-box" id="men2">
    <div class="product-content" id="mencontent2">Empty</div>
    <button class="assign-btn" onclick="assignProduct('mencontent2')">Assign</button>
  </div>
  <!-- Product Box 4 -->
  <div class="product-box" id="men3">
    <div class="product-content" id="mencontent3">Empty</div>
    <button class="assign-btn" onclick="assignProduct('mencontent3')">Assign</button>
  </div>
  <!-- Product Box 5 -->
  <div class="product-box" id="men4">
    <div class="product-content" id="mencontent4">Empty</div>
    <button class="assign-btn" onclick="assignProduct('mencontent4')">Assign</button>
  </div>
  <!-- Product Box 6 -->
  <div class="product-box" id="men5">
    <div class="product-content" id="mencontent5">Empty</div>
    <button class="assign-btn" onclick="assignProduct('mencontent5')">Assign</button>
  </div>
  <!-- Product Box 7 -->
  <div class="product-box" id="men6">
    <div class="product-content" id="mencontent6">Empty</div>
    <button class="assign-btn" onclick="assignProduct('mencontent6')">Assign</button>
  </div>
  <!-- Product Box 8 -->
  <div class="product-box" id="men7">
    <div class="product-content" id="mencontent7">Empty</div>
    <button class="assign-btn" onclick="assignProduct('mencontent7')">Assign</button>
  </div>
</div>

<h2>Women</h2>
<div class="box-grid">
  <!-- Product Box 1 -->
  <div class="product-box" id="women0">
    <div class="product-content" id="womencontent0">Empty</div>
    <button class="assign-btn" onclick="assignProduct('womencontent0')">Assign</button>
  </div>
  <!-- Product Box 2 -->
  <div class="product-box" id="women1">
    <div class="product-content" id="womencontent1">Empty</div>
    <button class="assign-btn" onclick="assignProduct('womencontent1')">Assign</button>
  </div>
  <!-- Product Box 3 -->
  <div class="product-box" id="women2">
    <div class="product-content" id="womencontent2">Empty</div>
    <button class="assign-btn" onclick="assignProduct('womencontent2')">Assign</button>
  </div>
  <!-- Product Box 4 -->
  <div class="product-box" id="women3">
    <div class="product-content" id="womencontent3">Empty</div>
    <button class="assign-btn" onclick="assignProduct('womencontent3')">Assign</button>
  </div>
  <!-- Product Box 5 -->
  <div class="product-box" id="women4">
    <div class="product-content" id="womencontent4">Empty</div>
    <button class="assign-btn" onclick="assignProduct('womencontent4')">Assign</button>
  </div>
  <!-- Product Box 6 -->
  <div class="product-box" id="women5">
    <div class="product-content" id="womencontent5">Empty</div>
    <button class="assign-btn" onclick="assignProduct('womencontent5')">Assign</button>
  </div>
  <!-- Product Box 7 -->
  <div class="product-box" id="women6">
    <div class="product-content" id="womencontent6">Empty</div>
    <button class="assign-btn" onclick="assignProduct('womencontent6')">Assign</button>
  </div>
  <!-- Product Box 8 -->
  <div class="product-box" id="women7">
    <div class="product-content" id="womencontent7">Empty</div>
    <button class="assign-btn" onclick="assignProduct('womencontent7')">Assign</button>
  </div>
</div>

<script>
function assignProduct(boxId) {
  fetch('get_live_products.php')
    .then(response => response.json())
    .then(data => {
      if (data.length === 0) {
        alert("No live products found.");
        return;
      }

      let productList = data.map(p => `<option value="${p}">${p}</option>`).join('');
      let selectHTML = `
        <select id="product-select">
          ${productList}
        </select>
      `;
      const modal = window.open("", "SelectProduct", "width=300,height=200");
      modal.document.write(`<p>Select a product:</p>${selectHTML}<br><br>
        <button onclick="window.opener.setProduct('${boxId}', document.getElementById('product-select').value); window.close();">Assign</button>`);
    })
    .catch(err => {
      alert("Error fetching products.");
      console.error(err);
    });
}

function setProduct(boxId, productName) {
  document.getElementById(boxId).innerText = productName;
}
</script>

</body>
</html>
