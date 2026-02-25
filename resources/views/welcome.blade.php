<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMC Portal — Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-950 min-h-screen flex flex-col items-center justify-center p-4">

    {{-- Header --}}
    <div class="text-center mb-10">
        <p class="text-yellow-300 text-sm font-light tracking-widest uppercase">
            Republic of the Philippines · Province of La Union
        </p>
        <h1 class="text-white text-4xl font-bold mt-2">LA UNION MEDICAL CENTER</h1>
        <p class="text-blue-300 mt-1">Hospital Information System</p>
        <p class="text-blue-400 text-sm mt-1">Agoo, La Union · LA UNION: Agkaysa!</p>
    </div>

    {{-- Portal Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 max-w-4xl w-full mb-10">

        <a href="/admin/login"
           class="bg-blue-800 hover:bg-blue-700 text-white rounded-2xl p-6 text-center transition shadow-lg border border-blue-600">
            <div class="text-4xl mb-3">🛡️</div>
            <h2 class="text-lg font-bold">Admin Portal</h2>
            <p class="text-blue-300 text-sm mt-1">User management, logs, schedules</p>
        </a>

        <a href="/doctor/login"
           class="bg-teal-800 hover:bg-teal-700 text-white rounded-2xl p-6 text-center transition shadow-lg border border-teal-600">
            <div class="text-4xl mb-3">🩺</div>
            <h2 class="text-lg font-bold">Doctor Portal</h2>
            <p class="text-teal-300 text-sm mt-1">Assessments, orders, patient charts</p>
        </a>

        <a href="/nurse/login"
           class="bg-rose-800 hover:bg-rose-700 text-white rounded-2xl p-6 text-center transition shadow-lg border border-rose-600">
            <div class="text-4xl mb-3">💉</div>
            <h2 class="text-lg font-bold">Nurse Portal</h2>
            <p class="text-rose-300 text-sm mt-1">Nurse's notes, orders, schedule</p>
        </a>

        <a href="/clerk/login"
           class="bg-amber-700 hover:bg-amber-600 text-white rounded-2xl p-6 text-center transition shadow-lg border border-amber-500">
            <div class="text-4xl mb-3">📋</div>
            <h2 class="text-lg font-bold">Clerk Portal</h2>
            <p class="text-amber-200 text-sm mt-1">OPD/ER registration, vitals entry</p>
        </a>

        <a href="/tech/login"
           class="bg-orange-700 hover:bg-orange-600 text-white rounded-2xl p-6 text-center transition shadow-lg border border-orange-500">
            <div class="text-4xl mb-3">🔬</div>
            <h2 class="text-lg font-bold">Tech Portal</h2>
            <p class="text-orange-200 text-sm mt-1">Lab & radiology result uploads</p>
        </a>

    </div>

    {{-- Footer --}}
    <footer class="text-blue-400 text-xs text-center">
        <p>Tel: (072) 607-5541-45 / (072) 607-5938 &nbsp;|&nbsp; ER: 0927-728-6330</p>
        <p class="mt-1">launionmedicalcenter@gmail.com &nbsp;|&nbsp; www.launion.gov.ph</p>
    </footer>

</body>
</html>