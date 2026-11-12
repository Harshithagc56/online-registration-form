<?php
require_once "database.php";

$courses = [
    "B.E. Computer Science",
    "B.E. Information Science",
    "B.E. Electronics",
    "BCA",
    "B.Com",
    "BBA"
];

$values = ["name"=>"", "email"=>"", "phone"=>"", "course"=>"", "gender"=>"", "dob"=>""];
$errors = [];
$submitted = false;
$dbError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($values as $key => $unused) {
        $values[$key] = trim($_POST[$key] ?? "");
    }

    if (mb_strlen($values["name"]) < 2) $errors["name"] = "Enter your name.";
    if (!filter_var($values["email"], FILTER_VALIDATE_EMAIL)) $errors["email"] = "Enter a valid email.";
    if (!preg_match('/^[0-9]{10}$/', $values["phone"])) $errors["phone"] = "Enter a 10-digit phone number.";
    if (!in_array($values["course"], $courses, true)) $errors["course"] = "Select a course.";
    if (!in_array($values["gender"], ["Female", "Male", "Prefer not to say"], true)) $errors["gender"] = "Select a gender option.";

    $date = DateTime::createFromFormat("Y-m-d", $values["dob"]);
    if (!$date || $date->format("Y-m-d") !== $values["dob"] || $values["dob"] > date("Y-m-d")) {
        $errors["dob"] = "Select a valid date.";
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO registrations (full_name, email, phone, course, gender, date_of_birth)
                 VALUES (:name, :email, :phone, :course, :gender, :dob)"
            );
            $stmt->execute([
                ":name" => $values["name"],
                ":email" => $values["email"],
                ":phone" => $values["phone"],
                ":course" => $values["course"],
                ":gender" => $values["gender"],
                ":dob" => $values["dob"]
            ]);
            $submitted = true;
        } catch (PDOException $e) {
            $dbError = "Unable to save your details. Please try again.";
        }
    }
}

function esc($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <main class="container">
        <h1>Registration Form</h1>
        <p class="subtitle">Please enter your details below.</p>

        <?php if ($submitted): ?>
            <section class="success">
                <h2>Registration Successful!</h2>
                <p>Your details have been saved.</p>
                <div class="details">
                    <p><strong>Name:</strong> <?= esc($values["name"]) ?></p>
                    <p><strong>Email:</strong> <?= esc($values["email"]) ?></p>
                    <p><strong>Phone:</strong> <?= esc($values["phone"]) ?></p>
                    <p><strong>Course:</strong> <?= esc($values["course"]) ?></p>
                    <p><strong>Gender:</strong> <?= esc($values["gender"]) ?></p>
                    <p><strong>Date of Birth:</strong> <?= esc($values["dob"]) ?></p>
                </div>
                <a class="button" href="index.php">Register Another</a>
            </section>
        <?php else: ?>
            <?php if ($dbError): ?>
                <p class="message"><?= esc($dbError) ?></p>
            <?php endif; ?>

            <form id="registrationForm" method="POST" action="index.php" novalidate>
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" value="<?= esc($values["name"]) ?>">
                <small class="field-error"><?= $errors["name"] ?? "" ?></small>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" value="<?= esc($values["email"]) ?>">
                <small class="field-error"><?= $errors["email"] ?? "" ?></small>

                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" maxlength="10" inputmode="numeric" placeholder="10-digit phone number" value="<?= esc($values["phone"]) ?>">
                <small class="field-error"><?= $errors["phone"] ?? "" ?></small>

                <label for="course">Course</label>
                <select id="course" name="course">
                    <option value="">Select a course</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= esc($course) ?>" <?= $values["course"] === $course ? "selected" : "" ?>><?= esc($course) ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="field-error"><?= $errors["course"] ?? "" ?></small>

                <label>Gender</label>
                <div class="radio-group">
                    <?php foreach (["Female", "Male", "Prefer not to say"] as $gender): ?>
                        <label class="radio-option">
                            <input type="radio" name="gender" value="<?= esc($gender) ?>" <?= $values["gender"] === $gender ? "checked" : "" ?>>
                            <?= esc($gender) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <small class="field-error"><?= $errors["gender"] ?? "" ?></small>

                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" max="<?= date("Y-m-d") ?>" value="<?= esc($values["dob"]) ?>">
                <small class="field-error"><?= $errors["dob"] ?? "" ?></small>

                <button type="submit">Submit</button>
                <p id="clientError" class="message" role="alert"></p>
            </form>
        <?php endif; ?>
    </main>
    <script src="script.js"></script>
</body>
</html>
