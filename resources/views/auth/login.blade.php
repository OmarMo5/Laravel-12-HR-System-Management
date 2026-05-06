<!DOCTYPE html>
<html lang="ar" dir="rtl" class="light">
<head>
    <meta charset="utf-8">
    <title>تسجيل الدخول | ASC HR System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/starcode2.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Tajawal', sans-serif !important; }
        .custom-input { color: #0f172a !important; } /* Force dark text color */
        .bg-custom-500 { background-color: #2563eb; }
        .login-label { color: #2e2d2dff !important; }
        .dark .login-label { color: #ffffff !important; }
        
        /* أنيمشن الاختفاء */
        .fade-out {
            animation: fadeOut 0.6s ease forwards;
        }
        
        /* أنيمشن الظهور */
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-15px); visibility: hidden; display: none; }
        }
        
        /* تنسيق النقاط الجديدة - نفس تنسيق النص القديم بالضبط */
        .feature-point {
            margin-bottom: 20px;
            padding-right: 25px;
            position: relative;
            line-height: 1.6;
            font-size: 1.05rem;
            font-weight: 500;
            color: #f1f5f9;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
            border-right: 3px solid #3b82f6;
            transition: all 0.3s ease;
        }
        
        .feature-point:before {
            content: "●";
            position: absolute;
            right: 0;
            color: #3b82f6;
            font-size: 1rem;
            top: 0;
        }
        
        /* تحسين للشاشات الصغيرة */
        @media (max-width: 1024px) {
            .feature-point { font-size: 0.9rem; margin-bottom: 15px; }
        }
    </style>
</head>
<body class="bg-white dark:bg-zink-950 overflow-x-hidden">

<div class="flex flex-col lg:flex-row min-h-screen w-full">
    
    <!-- Right Side: Background Image & Description -->
    <div class="hidden lg:flex lg:w-[60%] relative overflow-hidden">
        <img src="{{ asset('assets/images/login-bg.png') }}" class="absolute inset-0 w-full h-full object-cover" alt="ASC Background">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]"></div>
        
        <div class="relative z-10 flex h-full flex-col justify-center px-16 text-white text-right w-full">
            <div class="max-w-3xl mr-auto">
                <div class="mb-8 inline-block py-2 px-6 border-r-8 border-blue-500 bg-white/10 backdrop-blur-md">
                     <span class="text-white font-black text-5xl tracking-tighter italic">ASC</span>
                </div>
                <h1 class="text-6xl font-black mb-8 leading-[1.2]">
                    عالم من <span class="text-blue-500 underline decoration-8 underline-offset-8">الابتكار</span> <br>والتميز المعرفي
                </h1>
                
                <!-- نفس الصندوق - نفس المكان -->
                <div class="p-10 rounded-[2.5rem] bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl min-h-[300px]">
                    
                    <!-- النص الطويل (اللي هيختفي بسرعة) -->
                    <div id="initialText" class="text-xl leading-relaxed text-slate-100 font-medium">
                        ننشئ وننظم وندير معارض ومتاحف محلية ودولية لكل المجالات الفكرية والدينية والإنسانية، وننظم الفعاليات والمؤتمرات في كل المجالات وفي أي بقعة جغرافية وفق أعلى معايير الجودة المبنية على البحث العلمي، وأدوات العرض التقنية والتكنولوجية التفاعلية، والمعتمدة على الذكاء الاصطناعي والواقع الافتراضي الذي يحاكي الحقيقة بدقة كبيرة، ويوفر للزائرين متعة سمعية وبصرية تفاعلية فريدة.
                    </div>
                    
                    <!-- القائمة الجديدة (مخفية في البداية) -->
                    <div id="pointsList" style="display: none;">
                        <div class="feature-point">منصة موحدة لإدارة الموارد البشرية</div>
                        <div class="feature-point">تنظيم العمليات اليومية بكفاءة عالية</div>
                        <div class="feature-point">تقليل الأخطاء وزيادة الإنتاجية</div>
                        <div class="feature-point">دعم كامل لقرارات الإدارة</div>
                        <div class="feature-point">حماية وأمان للبيانات</div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Left Side: Login Form -->
    <div class="flex-1 flex flex-col items-center justify-center p-8 lg:p-20 bg-white dark:bg-zink-900 shadow-2xl">
        <div class="w-full max-w-[420px]">
            <div class="text-center mb-12">
                <img src="{{ asset('assets/images/ASC.png') }}" alt="ASC Logo" class="h-24 mx-auto mb-8 drop-shadow-xl">
                <h2 class="text-4xl font-black text-slate-800 dark:text-white mb-2">مرحباً بعودتك!</h2>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">نظام إدارة الموارد البشرية</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                @if(session('error'))
                    <div class="p-4 mb-4 text-sm text-red-500 border border-red-200 rounded-xl bg-red-50 font-bold">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="space-y-2">
                    <label class="block text-sm font-black tracking-wide login-label">البريد الإلكتروني / اسم المستخدم</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <input type="text" name="email" required 
                            class="custom-input block w-full pl-12 pr-4 py-4 border-2 border-slate-100 rounded-2xl bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-500 transition-all font-bold"
                            placeholder="أدخل البريد الإلكتروني أو اسم المستخدم">
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-black tracking-wide login-label">كلمة المرور</label>
                        <a href="#" class="text-xs font-bold text-blue-500 hover:underline">نسيت كلمة المرور؟</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input type="password" name="password" required
                            class="custom-input block w-full pl-12 pr-4 py-4 border-2 border-slate-100 rounded-2xl bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-500 transition-all font-bold"
                            placeholder="أدخل كلمة المرور">
                    </div>
                </div>

                <div class="flex items-center">
                    <input name="remember" class="w-5 h-5 border-slate-300 text-blue-600 rounded cursor-pointer" type="checkbox">
                    <label class="mr-3 text-sm font-bold text-slate-600 dark:text-slate-300 cursor-pointer">تذكرني</label>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-lg shadow-xl shadow-blue-500/30 hover:bg-blue-700 transition-all active:scale-95">
                        تسجيل الدخول
                    </button>
                </div>

                <div class="relative text-center my-10 py-4">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
                    <span class="relative px-4 bg-white text-xs font-bold text-slate-400 uppercase tracking-widest">أو سجل دخول بواسطة</span>
                </div>

                <div class="flex justify-center gap-5">
                    <a href="#" class="w-12 h-12 rounded-full flex items-center justify-center bg-[#1877F2] text-white shadow-lg hover:-translate-y-1 transition-transform">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="w-12 h-12 rounded-full flex items-center justify-center bg-[#DB4437] text-white shadow-lg hover:-translate-y-1 transition-transform">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="w-12 h-12 rounded-full flex items-center justify-center bg-[#1DA1F2] text-white shadow-lg hover:-translate-y-1 transition-transform">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="w-12 h-12 rounded-full flex items-center justify-center bg-[#333] text-white shadow-lg hover:-translate-y-1 transition-transform">
                        <i data-lucide="github" class="w-5 h-5"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets/libs/lucide/umd/lucide.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) {
            lucide.createIcons();
        }
        
        const initialTextDiv = document.getElementById('initialText');
        const pointsListDiv = document.getElementById('pointsList');
        
        // بعد 1.5 ثانية بس - النص الطويل يختفي بسرعة
        setTimeout(function() {
            if (initialTextDiv) {
                initialTextDiv.classList.add('fade-out');
            }
            
            // بعد ما يختفي، نظهر النقط في نفس المكان
            setTimeout(function() {
                if (initialTextDiv) {
                    initialTextDiv.style.display = 'none';
                }
                if (pointsListDiv) {
                    pointsListDiv.style.display = 'block';
                    
                    // نجعل كل نقطة تظهر واحدة ورا التانية بشكل احترافي
                    const points = pointsListDiv.querySelectorAll('.feature-point');
                    points.forEach((point, index) => {
                        point.style.opacity = '0';
                        point.style.transform = 'translateX(20px)';
                        point.style.transition = `all 0.4s ease ${index * 0.12}s`;
                        
                        setTimeout(() => {
                            point.style.opacity = '1';
                            point.style.transform = 'translateX(0)';
                        }, 100);
                    });
                }
            }, 600); // نفس مدة الأنيمشن
        }, 2000); // 1.5 ثانية بس - أسرع زي ما طلبت
    });
</script>
</body>
</html>