<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Page</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">
</head>
<body>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="message">
            <span>{{ $error }}</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
    @endforeach
@endif

@if (session('success'))
    <div class="message">
        <span>{{ session('success') }}</span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
@endif

<section class="form-container">
    <form action="{{ route('admin.login.post') }}" method="POST">
        @csrf
        <h3>login now</h3>
        
        <input type="text" 
               name="name" 
               required 
               placeholder="enter your username" 
               maxlength="20" 
               class="box" 
               oninput="this.value = this.value.replace(/\s/g, '')"
               value="{{ old('name') }}">
        
        <input type="password" 
               name="password" 
               required 
               placeholder="enter your password" 
               maxlength="20" 
               class="box" 
               oninput="this.value = this.value.replace(/\s/g, '')">
        
        <input type="submit" value="login now" class="btn" name="submit">
    </form>
</section>
   
</body>
</html>
