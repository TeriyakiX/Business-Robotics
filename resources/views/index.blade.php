<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.75, user-scalable=yes">
    <title>Business Robotics — AI-автоматизация нового поколения</title>
    <meta name="description" content="Business Robotics — AI-агенты для автоматизации бизнеса">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Business Robotics — AI-автоматизация нового поколения">
    <meta property="og:description" content="AI-агенты для автоматизации обзвона, записи клиентов и поддержки 24/7">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:locale" content="ru_RU">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div id="app"></div>
</body>
</html>
