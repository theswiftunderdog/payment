<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Xendit Payment Integration</title>
</head>
<body>
    <h2>Payment Form</h2>
    <form action="pay.php" method="POST">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" required><br><br>

        <label for="card_number">Card Number:</label>
        <input type="text" id="card_number" name="card_number" required><br><br>

        <label for="exp_month">Expiry Month:</label>
        <input type="number" id="exp_month" name="exp_month" required><br><br>

        <label for="exp_year">Expiry Year:</label>
        <input type="number" id="exp_year" name="exp_year" required><br><br>

        <label for="cvv">CVV:</label>
        <input type="number" id="cvv" name="cvv" required><br><br>

        <button type="submit">Pay</button>
    </form>
</body>
</html>
