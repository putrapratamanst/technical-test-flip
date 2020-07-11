<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        input[type=text],
        input[type=number] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 6px;
            margin-bottom: 16px;
            resize: vertical;
        }

        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        .container {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>

<body>

    <center>
        <h3>Disburse System</h3>
    </center>

    <div class="container">
        <form action="disburse" method="POST">
            <label for="bank_code">Bank Code</label>
            <input type="text" id="bank_code" name="bank_code" maxlength="5" required>

            <label for="account_number">Account Number</label>
            <input type="number" id="account_number" name="account_number" required>

            <label for="amount">Amount</label>
            <input type="number" id="amount" name="amount" min="0" required>

            <label for="remark">Remark</label>
            <input type="text" id="remark" name="remark" required>

            <input type="submit" value="Submit" onclick="getInput()">
        </form>
    </div>


    <table id="table">
        <tr>
            <th>REF ID</th>
            <th>Bank Code</th>
            <th>Account Number</th>
            <th>Amount</th>
            <th>Remark</th>
            <th>Fee</th>
            <th>Receipt</th>
            <th>Time Served</th>
            <th>Status</th>
        </tr>
    </table>
</body>

</html>
<script src="/assets/js/ajax.js"></script>

<?php
if(isset($_POST) && !empty($_POST)){
    die(json_encode($_POST));
}
?>
