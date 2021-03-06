<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sigh Up</title>
    <link href="css/signup.css" rel="stylesheet">
    <script src="js/scripts.js"></script>
</head>
<body>

<?php
// define variables and set to empty values
$usernameErr = $emailErr = $passwordErr = $accountTypeErr = "";
$paymentErr = $ccNameErr = $ccNumberErr = $ccExpirationErr = "";
$baNumberErr = $transitNumberErr = "";

$username = $email = $password = $accountType = "";
$payment = $ccName = $ccNumber = $ccExpiration = "";
$baNumber = $transitNumber = "";

// validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "POST REQUEST";
    // validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // validate username
    if (empty($_POST["username"])) {
        $usernameErr= "Username is required";
    } else {
        $username = test_input($_POST["username"]);
        // check if username is valid
        if (!preg_match("/^[a-zA-Z ]*$/",$username)) {
            $usernameErr = "Only letters and white space allowed";
        }
    }

    // validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = trim($_POST["password"]);
        echo $_POST["password"];
        if (strlen($password) < 6) {
            $passwordErr = "Password should have at least 6 characters without space in the beginning and ending";
        }
    }

    // validate account type
    if (empty($_POST["accountType"])) {
        $accountTypeErr = "Please choose one account type";
    } else {
        echo $_POST["accountType"];
    }

    // validate payment method
    if (empty($_POST["paymentRadio"])) {
        $paymentErr = "Please choose one payment method";
    } else {
        $payment = $_POST["paymentRadio"];
        echo $_POST["paymentRadio"];
    }

    // validate credit card information
    if ($payment == "creditCard") {
        if (empty($_POST["ccName"])) {
            $ccNameErr = "Credit Card Name should not be empty";
        } else {
            $ccName = test_input($_POST["ccName"]);
            // check if ccname is valid
            if (!preg_match("/^[a-zA-Z ]*$/",$ccName)) {
                $ccNameErr = "Only letters and white space allowed";
            }
        }

        if (empty($_POST["ccNumber"])) {
            $ccNumberErr = "Credit Card Number should not be emtpy";
        } else {
            $ccNumber = test_input($_POST["ccNumber"]);
            // check if ccNumber is valid
            if (!preg_match("/^[0-9]*$/", $ccNumber)) {
                $ccNumberErr = "Only digits allowed";
            }
        }

        if (empty($_POST["ccExpiration"])) {
            $ccExpirationErr = "Credit Expiration Date should not be empty";
        } else {
            $ccExpiration = test_input($_POST["ccExpiration"]);
            if (!preg_match("/^(0[1-9]|1[0-2])20([2-9][0-9])$/", $ccExpiration)) {
                $ccExpirationErr = "Expiration Date must be formatted as MMYYYY";
            }
        }
    }
    else if ($payment == "bankAccount") {  // validate bank account information
        if (empty($_POST["baNumber"])) {
            $baNumberErr = "Bank Account Number should not be empty";
        } else {
            $baNumber = test_input($_POST["baNumber"]);
        }

        if (empty($_POST["transitNumber"])) {
            $transitNumberErr = "Transit Number should not be empty";
        } else {
            $transitNumber = test_input($_POST["transitNumber"]);
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    echo "GET REQUEST";
}


function test_input($data) {
    $data = trim($data);  // Strip whitespace (or other characters) from the beginning and end of a string
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>


<div class="form">
    <h2 class="topBanner">Sign up for new account</h2>
    <p class="fillIn"><b>Please fill in this form to create an account</b></p>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="email"><p><b>Email</b></p></label>
        <input type="text" placeholder="Enter email" id="email" name="email" value="<?php echo $email;?>">
        <span class="error">* <?php echo $emailErr;?></span>

        <label for="username"><p><b>Username</b></p></label>
        <input type="text" placeholder="Enter username" id="username" name="username" value="<?php echo $username;?>">
        <span class="error">* <?php echo $usernameErr;?></span>

        <label for="password"><p><b>Password</b></p></label>
        <input type="password" placeholder="Enter password" id="password" name="password" value="<?php echo $password;?>">
        <span class="error">* <?php echo $passwordErr;?></span>

        <br/>
        <p style="display: inline-block"><b>Account type</b></p>
        <span class="error">* <?php echo $accountTypeErr;?></span>
        <div onclick="changePrice()" class="account-toolbar">
            <input type="radio" id="employerPrime" name="accountType" value="employerPrime">
            <label for="employerPrime">Employer - Prime</label>
            <input type="radio" id="employerGold" name="accountType" value="employerGold">
            <label for="employerGold">Employer - Gold</label>
            <br>
            <input type="radio" id="seekerBasic" name="accountType" value="seekerBasic">
            <label for="seekerBasic">Job Seeker - Basic</label>
            <input type="radio" id="seekerPrime" name="accountType" value="seekerPrime">
            <label for="seekerPrime">Job Seeker - Prime</label>
            <input type="radio" id="seekerGold" name="accountType" value="seekerGold">
            <label for="seekerGold">Job Seeker - Gold</label>
        </div>
        <p id="cost"><b>Monthly cost $ </b></p>

        <p><b>How would you like to pay?</b></p>
        <span class="error">* <?php echo $paymentErr;?></span>
        <div class="row">
            <!-- Credit Card column-->
            <div class="column">
                <input type="radio" id="creditRadio" name="paymentRadio" value="creditCard"
                    <?php if (isset($payment) && $payment=="creditCard") echo "checked";?>>
                <label for="creditRadio">Credit Card</label>

                <label for="ccName"><p><b>Name</b></p></label>
                <span class="error">* <?php echo $ccNameErr;?></span>
                <input type="text" placeholder="Enter name" id="ccName" name="ccName" value="<?php echo $ccName;?>">

                <label for="ccNumber"><p><b>Credit card number</b></p></label>
                <span class="error">* <?php echo $ccNumberErr;?></span>
                <input type="text" placeholder="Enter card number" id="ccNumber" name="ccNumber" value="<?php echo $ccNumber;?>">

                <label for="ccExpiration"><p><b>Expiration(MMYYYY)</b></p></label>
                <span class="error">* <?php echo $ccExpirationErr;?></span>
                <input type="text" placeholder="Enter expiration" id="ccExpiration" name="ccExpiration" value="<?php echo $ccExpiration;?>">
            </div>

            <!-- Bank Account Column -->
            <div class="column">
                <input type="radio" id="bankAccount" name="paymentRadio" value="bankAccount"
                    <?php if (isset($payment) && $payment=="bankAccount") echo "checked";?>>
                <label for="bankAccount">Bank Account</label>

                <label for="baNumber"><p><b>Account number</b></p></label>
                <span class="error">* <?php echo $baNumberErr;?></span>
                <input type="text" placeholder="Enter account number" id="baNumber" name="baNumber" value="<?php echo $baNumber;?>">

                <label for="transitNumber"><p><b>Transit Number</b></p></label>
                <span class="error">* <?php echo $transitNumberErr;?></span>
                <input type="text" placeholder="Enter transit number" id="transitNumber" name="transitNumber" value="<?php echo $transitNumber ?>">
            </div>
        </div>

<!--        cancel the input values, and reload page with get request-->
        <input type="button" onclick="location.href=location.href" value="Cancel" class="cancel">
<!--        submit input values, reload page with post request-->
        <input type="submit" value="Make Account" class="make">
    </form>

</div>
</body>

