<?php require 'includes/header.php' ?>
    <h1 class="d-flex justify-content-center p-5">Welcome to the discount page!</h1>
    <section class="min-vh-100">
        <div class="d-flex justify-content-center p-1">
            <form method="post" class="form-inline">
                <select class="custom-select mr-sm-1" name="productId">
                    <option disabled selected value> select a product</option>
                    <?php foreach ($products as $product):
                        var_dump($products); ?>
                        <option value="<?php echo $product['id']; ?>"><?php echo $product['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="custom-select mr-sm-1" name="customerId">
                    <option disabled selected value> select a person</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo $customer['id']; ?>"><?php echo $customer['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" name="submit" class="btn btn-primary mb-2">
            </form>
    </section>
    <div class="d-flex justify-content-center p-1">
        <?php if (isset($calculatedPrice)): ?>
            <h3><?php echo $customername . " has to pay &euro; " . $calculatedPrice . " for a(n) " . $productName; ?></h3>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
    </div>


    <div class="d-flex justify-content-center p-1">
        <table class="table table-striped w-25 table-bordered">
            <thead class="thead-dark">
            <tr>
                <th> Original price</th>
                <th> Total Discount</th>
                <th> New Price</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <?php if (isset($calculatedPrice)): ?>
                <td> <?php echo '&euro;' . $productPrice  ?> </td>
                <td> <?php echo '&euro;' . $calculator->getTotalDiscount() ?> </td>
                <td> <?php echo '&euro;' . $calculatedPrice ?> </td>
               <?php endif; ?>
            </tr>
            </tbody>

        </table>
    </div>

    <form method="post">
        <button name="logout">Log out</button>
    </form>

<?php require 'includes/footer.php'; ?>