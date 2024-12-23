<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Coding Question</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            display: flex;
            flex-direction: column;
            width: 300px;
        }

        label, textarea, input, select, button {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="submit_challenge.php" method="post">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Description</label>
            <textarea name="description" id="description" required></textarea>

            <label for="difficulty">Difficulty</label>
            <select name="difficulty" id="difficulty" required>
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>
            </select>

            <label for="input">Test Case</label>
            <textarea name="input" id="input" required></textarea>

            <label for="output">Expected Output</label>
            <textarea name="output" id="output" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>

