<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenges</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/clike/clike.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/python/python.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .challenge {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: #fff;
            transition: box-shadow 0.3s;
        }

        .challenge:hover {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .challenge h2 {
            margin-top: 0;
            color: #333;
            font-size: 1.5em;
        }

        .challenge p {
            margin: 10px 0;
            color: #666;
        }

        .challenge .description {
            margin-bottom: 15px;
        }

        .challenge pre {
            white-space: pre-wrap;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            margin-top: 5px;
        }

        .solve-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .solve-btn:hover {
            background-color: #0056b3;
        }

        .editor-container {
            display: none;
            margin-top: 20px;
            border-radius: 8px;
            padding: 20px;
            background-color: #fff;
        }

        .editor {
            height: 300px;
        }

        .btn-container {
            margin-top: 10px;
        }

        .btn-container button {
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-container button.submit-btn {
            background-color: #28a745;
            color: #fff;
        }

        .btn-container button.run-btn {
            background-color: #17a2b8;
            color: #fff;
        }

        .btn-container button:hover {
            opacity: 0.8;
        }

        .language-select {
            margin-top: 10px;
            background-color: #4CAF50;
            padding: 10px;
            display: flex;
            justify-content: flex-end;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .language-select select {
            width: 120px;
            border: none;
            outline: none;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
            padding: 8px;
            border-radius: 3px;
            appearance: none;
            -webkit-appearance: none;
        }

        .language-select::after {
            content: "\25BC";
            color: white;
            position: absolute;
            top: 15px;
            right: 10px;
            pointer-events: none;
        }

        .language-select select:hover {
            background-color: #45a049;
        }

        .output-display {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            white-space: pre-wrap;
            overflow-wrap: break-word;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div id="challenge-list">
        <!-- Challenges rendered by PHP -->
        <?php
        include '../db.php';
        include 'challenges.php';
        ?>
    </div>

    <div id="editor-container" class="editor-container">

        <div class="language-select">
            <select id="language">
                <option value="c">C</option>
                <option value="c++">C++</option>
                <option value="Java">Java</option>
                <option value="Python">Python</option>
            </select>
        </div>
        <textarea name="editor" id="editor" class="editor" cols="100"></textarea>
        <div class="btn-container">
            <button class="submit-btn" onclick="submitCode()">Submit Code</button>
            <button class="run-btn" onclick="runCode()">Run Code</button>
        </div>
    </div>

    <div id="output-container" class="output-display">
        <!-- Output will be displayed here -->
    </div>
</div>

<script>
    var editor;
    var currentLanguage = 'c';

    function openEditor(questionId) {
        document.getElementById('challenge-list').style.display = 'none';
        document.getElementById('editor-container').style.display = 'block';

        editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
            lineNumbers: true,
            mode: getMode(currentLanguage),
            theme: 'default'
        });
        editor.setValue('// Write your code here in ' + currentLanguage);

        localStorage.setItem('questionId', questionId);

        document.getElementById('language').addEventListener('change', function() {
            currentLanguage = this.value;
            editor.setOption('mode', getMode(currentLanguage));
            if (currentLanguage == 'python') {
                editor.setValue('# Write your code here');
            } else {
                editor.setValue('// Write your code here in ' + currentLanguage);
            }
        });
    }

    function getMode(language) {
        switch (language) {
            case 'c':
                return 'text/x-csrc';
            case 'c++':
                return 'text/x-c++src';
            case 'Java':
                return 'text/x-java';
            case 'Python':
                return 'text/x-python';
            default:
                return '';
        }
    }


    function submitCode() {
        var questionId = localStorage.getItem('questionId');
        var code = editor.getValue();
        var language = document.getElementById('language').value;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'submit_code.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        var outputContainer = document.getElementById('output-container');
                        if (response.success) {
                            outputContainer.innerHTML = '<div style="color: green;">Code passed: ' + response.message + '</div>';
                        } else {
                            outputContainer.innerHTML = '<div style="color: red;">Code failed: ' + response.message + '<br>Output: ' + response.output + '</div>';
                        }
                    } catch (error) {
                        console.error('Error parsing JSON response: ', error);
                        alert('Error parsing JSON response');
                    }
                } else {
                    console.error('Request failed. Status: ' + xhr.status);
                    alert('Failed to submit code. Status: ' + xhr.status);
                }
            }
        };

        xhr.onerror = function() {
            console.error('Network request failed');
            alert('Network request failed');
        };

        var jsonData = JSON.stringify({
            questionId: questionId,
            code: code,
            language: language
        });

        xhr.send(jsonData);
    }

    function runCode() {
        var questionId = localStorage.getItem('questionId');
        var code = editor.getValue();
        var language = document.getElementById('language').value;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'run_code.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);

                        if (response.success) {
                            var outputContainer = document.getElementById('output-container');
                            outputContainer.innerHTML = '<pre>' + response.output + '</pre>';
                        } else {
                            var errorMessage = 'Execution failed: ' + response.message;
                            var outputContainer = document.getElementById('output-container');
                            outputContainer.innerHTML = '<div style="color: red;">' + errorMessage + '</div>';
                        }
                    } catch (error) {
                        console.error('Error parsing JSON response: ', error);
                        alert('Error parsing JSON response');
                    }
                } else {
                    console.error('Request failed. Status: ' + xhr.status);
                    alert('Failed to fetch data');
                }
            }
        };

        xhr.onerror = function() {
            console.error('Request failed. Network error.');
            alert('Network error. Please try again.');
        };

        var data = JSON.stringify({ questionId: questionId, code: code, language: language });
        xhr.send(data);
    }

</script>

</body>
</html>
