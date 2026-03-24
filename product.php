<!DOCTYPE html>
<html lang="en">
     <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="carousel.css" rel="stylesheet" />
<head>
    <style>
   body{
   background-color:rgb(255, 255, 255);
   }
    header {
  position: sticky;
  top: 0;
  background-color: #2f5d50;
  font-family: Arial, Helvetica, sans-serif;
  padding: 20px 40px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  z-index: 1000;
}


nav a {
  color: #ffffff;           
  font-size: 16px;
  font-weight: 600;             
  text-decoration: none;
  padding: 10px 15px;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
}
  /* 📱 Mobile Responsive */
@media (max-width: 768px) {
  .main-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .navbar {
    flex-wrap: wrap;
    gap: 15px;
  }
}

.menu-container {
      padding-top: 70px;
      text-align: center;
      font-style: italic;
      background-color: #eef4f1;
    }

    .menu-title {
     
      font-size: 30px;
      color: #000000;
      text-shadow: 1px 2px 3px #000;
      font-weight: bold;
    }
 

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 30px;
      max-width: 1000px;
      margin: 0 auto;
      padding: 20px ;
    }

    .menu-item {
      background: beige;
      border-radius: 10px;
      padding: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.15);
      transition: transform 0.3s;
    }

    .menu-item:hover {
      transform: scale(1.03);
    }

    .menu-item img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
    }

    .menu-item h3 {
      margin: 15px;
      font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
      font-size: large;
    }

    .menu-item p {
      font-weight: bold;
      color: #000000;
    }

   .quantity-input {
  width: 70px;
  padding: 5px;
  margin-top: 10px;
  font-size: 14px;
  text-align: center;
  border: 2px solid #ddd;
  border-radius: 8px;
  outline: none;
  
}
   
      .order-btn {
  margin-top: 10px;
  padding: 8px;
  background: linear-gradient(135deg, #28a745, #218838);
  color: white;
  font-size: 14px;
  font-weight: bold;
  border: none;
  border-radius: 25px;
  cursor: pointer;
 
}
 
  .menu-item .rating {
  color: #f5b301;
  font-size: 18px;
  letter-spacing: 2px;
  margin: 5px 0;
}


@media (max-width: 768px) {
  .product-img-holder {
    height: 180px;
  }

}
.label-box {
  margin-top: 8px;
  font-size: 14px;
}
#modalQty{
    width: 100px;
}
#name{
    width: 350px;
    border: none;
}


        </style>
    
</head>

   <body>
    <header>
    <section class="section1"> 
    <nav> 
      
    </nav>
    </section>
</header>
   <section class="product-section">
  <div class="product-grid">

   <?php
include 'db_connection.php';

$sql = "SELECT * FROM shop";
$result = $conn->query($sql);
?>

<section class="menu-container">
  <h1 class="menu-title"></h1>

  <div class="menu-grid">
    <?php
    if ($result->num_rows > 0) {
      while ($product = $result->fetch_assoc()) {
    ?>
        <div class="menu-item">
          <img src="<?php echo $product['product_image']; ?>" alt="product">

          <h3><?php echo $product['product_name']; ?></h3>
            
          <p class="rating">★★★★★</p>
<div class="label-box">
                <strong>Stocks Available:</strong> <?php echo $product['stocks']; ?>
            </div>

          <p>₱ <?php echo number_format($product['price'], 2); ?></p>

          <input type="number" min="1" value="1" class="quantity-input">

          <button class="order-btn" onclick="buyNow(<?php echo $product['product_id']; ?>)">
            Buy Now
          </button>
        </div>
        
    <?php
      }
    }
    ?>
  </div>
</section>

<?php
$conn->close();
?>

<script src="assets/dist/js/bootstrap.bundle.min.js"></script>
<!-- Buy Now Modal -->
<div class="modal fade" id="buyNowModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Checkout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-5">
            <img id="modalImage" src="" class="img-fluid rounded">
          </div>

          <div class="col-md-7">
            <h5 id="modalName"></h5>
            <p>Price: ₱ <span id="modalPrice"></span></p>
            <p>Available Stock: <span id="modalStock"></span></p>
           <div class="form-group">
              <label>Name:</label> <br> <input type="text" name="name" placeholder="Full Name"required> <br>

              <label>Address:</label> <br>
              <input type="text" id="customerAddress" placeholder="Address" required> <br>

              <label>Contact Number:</label> <br>
              <input type="number" id="customerContact" placeholder="Contact Number" required> <br>
            </div>

            <label>Quantity</label>
            <input type="number" id="modalQty" class="form-control" min="1" value="1">

            <h5 class="mt-3">
              Total: ₱ <span id="modalTotal"></span>
            </h5>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" onclick="confirmCheckout()">Checkout</button>
      </div>

    </div>
  </div>
</div>

<script>
let selectedProduct = {};

function buyNow(product_id) {
    fetch('get_product.php?id=' + product_id)
    .then(res => res.json())
    .then(data => {
        selectedProduct = data;

        document.getElementById('modalImage').src = data.product_image;
        document.getElementById('modalName').innerText = data.product_name;
        document.getElementById('modalPrice').innerText = data.price;
        document.getElementById('modalStock').innerText = data.stocks;
        document.getElementById('modalQty').value = 1;
        document.getElementById('modalTotal').innerText = data.price;

        document.getElementById('modalQty').max = data.stocks;

        new bootstrap.Modal(document.getElementById('buyNowModal')).show();
    });
}

document.getElementById('modalQty').addEventListener('input', function () {
    let qty = this.value;
    let total = qty * selectedProduct.price;
    document.getElementById('modalTotal').innerText = total.toFixed(2);
});

function confirmCheckout() {
    let qty = document.getElementById('modalQty').value;

    fetch('checkout.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `product_id=${selectedProduct.product_id}&quantity=${qty}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Checkout successful!');
            location.reload();
        } else {
            alert(data.message);
        }                                                              
    });
}
</script>

   </section>
</body>
</html>
