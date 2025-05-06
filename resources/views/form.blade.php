<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        form {
            max-width: 500px;
            background: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        input, select, button {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <h2>REGISTRATION FORM (FOR APPLICANTS)</h2>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('register.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <label for="district">SELECT DISTRICT:</label>
        <select id="district" name="district" required>
            <option value="">-- Select District --</option>
            <option value="District 1">District 1</option>
            <option value="District 2">District 2</option>
        </select>

        <label for="block">SELECT BLOCK:</label>
        <select id="block" name="block" required>
            <option value="">-- Select Block --</option>
            <option value="Block 1">Block 1</option>
            <option value="Block 2">Block 2</option>
        </select>

        <label for="gov_type">SELECT:</label>
        <select id="gov_type" name="gov_type" required>
            <option value="">-- Select --</option>
            <option value="gram_panchayat">Gram Panchayat</option>
            <option value="municipal_bodies">Municipal Bodies</option>
        </select>

        <label for="gp_mb_list">LIST OF GRAM PANCHAYAT / MUNICIPAL BODIES:</label>
        <select id="gp_mb_list" name="gp_mb_list" required>
            <option value="">-- Select from List --</option>
            <option value="GP 1">Gram Panchayat 1</option>
            <option value="GP 2">Gram Panchayat 2</option>
            <option value="MB 1">Municipal Body 1</option>
        </select>

        <label for="name">NAME OF PERSON:</label>
        <input type="text" id="name" name="name" required style="text-transform:uppercase;">

        <label for="designation">DESIGNATION:</label>
        <select id="designation" name="designation" required>
            <option value="">-- Select Designation --</option>
            <option value="sarpanch">Sarpanch</option>
            <option value="gram_sachiv">Gram Sachiv</option>
            <option value="ward_member">Ward Member</option>
            <option value="councillor">Councillor</option>
        </select>

        <!--<label>DOWNLOAD LETTER FORMAT:</label>
        <a href="{{ asset('letter_format.pdf') }}" download>Click Here to Download</a>

        <label for="declaration">UPLOAD LETTER OF DECLARATION:</label>
        <input type="file" id="declaration" name="declaration" required accept=".pdf,.jpg,.png">-->

        <button type="submit">SUBMIT REGISTRATION</button>
    </form>

</body>
</html>
