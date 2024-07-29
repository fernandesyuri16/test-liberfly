<!DOCTYPE html>
<html>
<head>
    <title>Holiday plan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .holiday-data {
            margin-bottom: 20px;
        }
        .holiday-data p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h1>{{ $viewData['userName'] }} holiday plan</h1>

    <div class="holiday-data">
        <p><strong>Title:</strong> {{ $viewData['title'] }}</p>
        <p><strong>Description:</strong> {{ $viewData['description'] }}</p>
        <p><strong>Date:</strong> {{ $viewData['date'] }}</p>
        <p><strong>Location:</strong> {{ $viewData['location']}}</p>
        <p><strong>Participants:</strong> {{ $viewData['participants'] }}</p>
    </div>
</body>
</html>
