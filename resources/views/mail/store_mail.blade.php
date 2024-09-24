<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Booking Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #0056b3;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section p {
            margin: 5px 0;
        }
        .section h3 {
            margin-top: 0;
            color: #333;
        }
        .section table {
            width: 100%;
            border-collapse: collapse;
        }
        .section table th,
        .section table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .section table th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Booking Received</h2>
        
        <!-- User Details -->
        <div class="section">
            <h3>User Details</h3>
            <p><strong>Name:</strong> {{ $mailData['user_fname'] . ' ' . $mailData['user_lname'] }}</p>
            <p><strong>Email:</strong> {{ $mailData['user_email'] }}</p>
        </div>

        <!-- Bike Details -->
        <div class="section">
            <h3>Bike Details</h3>
            <p><strong>Brand:</strong> {{ $mailData['bike_brand'] }}</p>
            <p><strong>Model:</strong> {{ $mailData['bike_model'] }}</p>
            @if($mailData['bike_detail'])
                <p><strong>Details:</strong> {{ $mailData['bike_detail'] }}</p>
            @endif
        </div>
        @php
            $relatedServices = $mailData['related_services_with_types'] ?? [];
            $servicesArray = is_array($relatedServices) ? $relatedServices : [];
        @endphp
    
        <div class="section" style="margin-bottom: 16px;">
            <h3>Service Details</h3>
            <p><strong>Service Date:</strong> {{ $mailData['service_date'] }}</p>
            <p><strong>Service Price:</strong> {{ $mailData['service_price'] }}</p>
        </div>
    
        <div style="margin-bottom: 16px;">
            @foreach($servicesArray as $service)
                @php
                    $typesArray = is_array($service['types']) ? $service['types'] : array_filter([$service['types']]);
                @endphp
        
                <div style="margin-left: 24px; margin-right: 24px; margin-bottom: 16px; display: flex; align-items: flex-start;">
                    <div style="flex: 1; margin-right: 16px;">
                        <h4>{{ $service['name'] ?? 'No service name' }}</h4>
                        @if(count($typesArray) > 0)
                            @foreach($typesArray as $type)
                                <div style="margin-bottom: 8px;">
                                    <p>
                                        {{ $type['name'] ?? 'No type name' }} - ${{ $type['club_price'] ?? 'No price' }}
                                    </p>
                                </div>
                            @endforeach
                        @else
                            <p>No service types available</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    
    </div>
</body>
</html>
