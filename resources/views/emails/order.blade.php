<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Confirmation - Al Hilal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #ffffff;
            padding: 30px 40px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .content {
            padding: 20px 40px;
        }

        .section-title {
            color: #333;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .label {
            font-weight: 500;
            color: #555;
        }

        .value {
            color: #000;
        }

        .info-section {
            margin-top: 30px;
        }

        .footer {
            background-color: #990200;
            color: #fff;
            padding: 25px;
            text-align: center;
            font-size: 13px;
            line-height: 1.6;
        }

        @media (max-width: 480px) {
            .content {
                padding: 20px;
            }

            .header {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <img src="https://admin.giftsaura.com/images/appimg/logo.jpg" alt="Al Hilal" width="220"
                style="margin-bottom: 15px" />
            <h1>Thank you for your order</h1>
            <p style="color: #777; font-size: 14px">
                Thank you {{ $orderData['name'] }} for shopping with Al Hilal!
            </p>
        </div>

        <div class="content">
            <div class="section-title">Order Details</div>

            <div class="row">
                <div class="label">Your Order Id: </div>
                <div class="value">
                    {{ $orderData['id'] }}
                </div>
            </div>
            <div class="row">
                <div class="label">Order Date: </div>
                <div class="value">
                    {{ \Carbon\Carbon::parse($orderData['created_at'])->format('F j, Y') }}
                </div>
            </div>
            <div class="row">
                <div class="label">Total Amount: </div>
                <div class="value">
                    {{ $orderData['total_price'] }}
                </div>
            </div>
            <div class="row">
                <div class="label">Delivery Type: </div>
                <div class="value">{{ $orderData['shipping_type'] }}</div>
            </div>

            <div class="info-section">
                <div class="section-title">Shipping Information</div>

                <div class="row">
                    <div class="label">Recipient Email: </div>
                    <div class="value">{{ $orderData['email'] }}</div>
                </div>
                <div class="row">
                    <div class="label">Address: </div>
                    <div class="value">{{ $orderData['address'] }}</div>
                </div>
                <div class="row">
                    <div class="label">City: </div>
                    <div class="value">{{ $orderData['city'] }}</div>
                </div>
                <div class="row">
                    <div class="label">Province: </div>
                    <div class="value">{{ $orderData['province'] }}</div>
                </div>
                <div class="row">
                    <div class="label">Country: </div>
                    <div class="value">{{ $orderData['country'] }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Have questions about your order?</p>
            <p>
                Email us at <strong style="color: white;">giftsaura1@gmail.com</strong><br />
                or Call / WhatsApp at <strong>+92 3020397144</strong>
            </p>
            <p>We appreciate your business!</p>
        </div>
    </div>
</body>

</html>
