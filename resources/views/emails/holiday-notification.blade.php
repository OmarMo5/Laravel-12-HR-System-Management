<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إشعار إجازة رسمية</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 0;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .holiday-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .holiday-name {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .holiday-date {
            font-size: 18px;
            opacity: 0.95;
        }
        .holiday-type {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            margin-top: 10px;
            font-size: 14px;
        }
        .info-box {
            background-color: #f8f9fa;
            border-right: 4px solid #2a5298;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .info-box h4 {
            margin: 0 0 10px;
            color: #2a5298;
        }
        .info-box ul {
            margin: 0;
            padding-right: 20px;
        }
        .info-box li {
            margin: 8px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
        }
        .button {
            display: inline-block;
            background-color: #2a5298;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .date-range-badge {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            padding: 8px 16px;
            border-radius: 30px;
            margin-top: 12px;
            font-size: 14px;
        }
        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }
            .holiday-name {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏢 نظام إدارة الموارد البشرية</h1>
            <p>إشعار رسمي بمناسبة الإجازة</p>
        </div>

        <div class="content">
            <h2>السادة / الموظفين الأفاضل،</h2>
            <p>تحية طيبة وبعد،</p>

            <div class="holiday-card">
                <div class="holiday-name">{{ $holiday->holiday_name }}</div>
                <div class="holiday-date">
                    📅 التاريخ: 
                    @php
                        $startDate = \Carbon\Carbon::parse($holiday->start_date);
                        $endDate = \Carbon\Carbon::parse($holiday->end_date);
                    @endphp
                    
                    @if($startDate->format('Y-m-d') == $endDate->format('Y-m-d'))
                        {{ $startDate->format('l d F Y') }}
                    @else
                        من {{ $startDate->format('l d F Y') }}
                        <br>
                        إلى {{ $endDate->format('l d F Y') }}
                    @endif
                </div>
                
                @if($startDate->format('Y-m-d') != $endDate->format('Y-m-d'))
                    <div class="date-range-badge">
                        📆 عدد الأيام: {{ $startDate->diffInDays($endDate) + 1 }} يوم
                    </div>
                @endif
                
                <div class="holiday-type">
                    🏷️ نوع الإجازة: {{ $holiday->holiday_type }}
                </div>
            </div>

            <div class="info-box">
                <h4>📌 تنبيهات هامة:</h4>
                <ul>
                    <li>✓ الإجازة رسمية لجميع العاملين بالشركة</li>
                    <li>✓ سيتم استئناف العمل في يوم <strong>{{ \Carbon\Carbon::parse($holiday->end_date)->addDay()->format('l d F Y') }}</strong></li>
                    <li>✓ أي عمل إضافي طارئ يتم التنسيق له مع الإدارة المختصة</li>
                    <li>✓ الإجازة لا تحتسب من رصيد الإجازات السنوية</li>
                </ul>
            </div>

            <div class="info-box">
                <h4>💡 ملاحظات:</h4>
                <ul>
                    <li>في حالة وجود أي استفسار، يرجى التواصل مع قسم الموارد البشرية</li>
                    <li>نتمنى لكم قضاء إجازة سعيدة</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <span class="button" style="background-color: #2a5298;">🏢 إدارة الموارد البشرية</span>
            </div>
        </div>

        <div class="footer">
            <p>هذا البريد إلكتروني آلي، يرجى عدم الرد عليه.</p>
            <p>&copy; {{ date('Y') }} جميع الحقوق محفوظة - نظام إدارة الموارد البشرية</p>
        </div>
    </div>
</body>
</html>