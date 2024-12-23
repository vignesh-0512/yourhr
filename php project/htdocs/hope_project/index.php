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

        .container{
            display: flex;
            justify-content: center;
            align-items: center;
            height:100vh;
        }

        form{
            display:flex;
            flex-direction: column;
            width: 200px;
        }
        label,textarea,button{
            margin-bottom: 10px;
        }

        input[type="text"]{
            margin-bottom:10px;
        }


    </style>
</head>
<body>
    <div class="container">
       <form action="submit_question.php" method="post">

            <label for="question">Title</label>
            <input type="text" name="title" id="title" required></input>

            <label for="question">Description</label>
            <textarea name="description" id="description" required></textarea>

            <label for="difficulty">Difficulty</label>
            <select name="difficulty" id="difficulty" required>
                <option value="easy">Easy</option>
                <option value="medium">medium </option>
                <option value="hard">Hard</option>
            </select>

            <!-- <label for="added_by">Added by</label>
            <input name="added_by" id="added_by" required style="margin-bottom:10px;"></input> -->

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
