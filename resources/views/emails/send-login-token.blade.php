<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Token</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }

    .container {
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      border-radius: 5px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .header {
      background-color: #007bff;
      color: #ffffff;
      padding: 20px;
      text-align: center;
    }

    .content {
      padding: 20px;
    }

    .footer {
      background-color: #f4f4f4;
      text-align: center;
      padding: 10px;
      font-size: 12px;
      color: #777777;
    }

    .button {
      display: inline-block;
      padding: 10px 20px;
      margin: 20px 0;
      background-color: #28a745;
      color: #ffffff;
      text-decoration: none;
      border-radius: 5px;
    }

  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Your Login Token</h1>
    </div>
    <div class="content">
      <p>Dear User,</p>
      <p>Here is your login token:</p>
      <h2>{{ $token }}</h2>
      <p>This token will expire in 10 minutes.</p>
    </div>
    <div class="footer">
      <p>If you did not request this token, please ignore this email.</p>
    </div>
  </div>
</body>
</html>
