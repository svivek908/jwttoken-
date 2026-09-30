
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Multiple File Upload</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 50px;
        }

        .upload-box {
            width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
        }

        input[type="file"] {
            width: 100%;
            margin: 20px 0;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background: #0056b3;
        }

        #message {
            margin-top: 20px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        .file-list {
            margin-top: 20px;
        }

    </style>

</head>

<body>

<div class="upload-box">

    <h2>Multiple File Upload</h2>

    <form id="uploadForm" enctype="multipart/form-data">

        <label>Select Files</label>

        <input
            type="file"
            name="files[]"
            id="files"
            multiple
        >

        <br>

        <button type="submit">
            Upload Files
        </button>

    </form>

    <div id="message"></div>

    <div class="file-list" id="fileList"></div>

</div>


<script>

$(document).ready(function () {

    $('#uploadForm').on('submit', function (e) {

        e.preventDefault();

        let files = $('#files')[0].files;

        // Check file selection
        if (files.length === 0) {

            $('#message').html(
                '<p class="error">Please select at least one file.</p>'
            );

            return;
        }

        let formData = new FormData();

        // Add multiple files
        for (let i = 0; i < files.length; i++) {

            formData.append('files[]', files[i]);

        }

        $('#message').html(
            '<p>Uploading...</p>'
        );

        $.ajax({

            url: "<?= base_url('upload-files') ?>",

            type: "POST",

            data: formData,

            processData: false,

            contentType: false,

            success: function (response) {

                console.log(response);

                if (response.status === true) {

                    $('#message').html(
                        '<p class="success">' +
                        response.message +
                        '</p>'
                    );


let html = '<h4>Uploaded Images:</h4>';

response.files.forEach(function (file) {

    html += `
        <div style="
            display:inline-block;
            margin:10px;
            text-align:center;
        ">

            <img
                src="${file.url}"
                width="150"
                height="150"
                style="
                    object-fit:cover;
                    border-radius:8px;
                    border:1px solid #ccc;
                "
            >

            <br>


        </div>
    `;

});

$('#fileList').html(html);


                    // Reset form
                    $('#uploadForm')[0].reset();

                } else {

                    $('#message').html(
                        '<p class="error">' +
                        response.message +
                        '</p>'
                    );

                }

            },

            error: function (xhr) {

                console.log(xhr);

                let message = 'Something went wrong.';

                if (xhr.responseJSON) {

                    message = xhr.responseJSON.message;

                }

                $('#message').html(
                    '<p class="error">' +
                    message +
                    '</p>'
                );

            }

        });

    });

});

</script>

</body>

</html>

