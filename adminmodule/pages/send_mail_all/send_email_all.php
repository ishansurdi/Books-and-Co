<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Mail to All | Library Admin Portal</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="adminportal.css">
    <style>
        .email-form {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .email-form h2 {
            text-align: center;
        }
        .email-form label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }
        .email-form input[type="text"],
        .email-form textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .email-form select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .email-form button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .email-form button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <section class="email-form">
            <h2>Send Mail to All</h2>
            <form action="send_mail_process.php" method="POST">
                <label for="announcement-type">Select Type:</label>
                <select name="announcement-type" id="announcement-type">
                    <option value="Announcement">Announcement</option>
                    <option value="Circular">Circular</option>
                    <option value="Notice">Notice</option>
                </select>

                <label for="subject">Subject:</label>
                <input type="text" name="subject" id="subject" required>

                <label for="body">Body:</label>
                <textarea name="body" id="body" rows="10" required></textarea>

                <label for="sequence-number">Next Sequence Number:</label>
                <input type="text" name="sequence-number" id="sequence-number" value="Auto Fetched from DB" readonly>

                <label for="send-to">Send To:</label>
                <select name="send-to" id="send-to">
                    <option>select</option>
                    <option value="all">Send to All Members</option>
                    <option value="individual">Send to Individual Member</option>
                </select>

                <button type="submit">Send Email</button>
            </form>
        </section>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('get_sequence_number.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('sequence-number').value = data.sequence_number;
                })
                .catch(error => console.error('Error fetching sequence number:', error));
        });
    </script>
</body>
</html>
