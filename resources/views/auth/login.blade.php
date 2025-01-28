<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Dashboard</title>
  <link rel="icon" type="image/png" href="{{ asset('storage/app/public/images/logo/logo.jpg') }}" sizes="16x16" />
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
  <div id="particles-js"></div>
  <form action="{{ route('login') }}" method="POST">
    @csrf
  <div class="ring">
    <i style="--clr:#00ff0a;"></i>
    <i style="--clr:#ff0057;"></i>
    <i style="--clr:#fffd44;"></i>
    <div class="login">
      <h2>Login</h2>
      <div class="inputBx">
        <input type="email" placeholder="Email" name="email">
      </div>
      <div class="inputBx">
        <input type="password" placeholder="Password" name="password">
      </div>
      <div class="inputBx">
        <input type="submit" value="Masuk">
      </div>
      {{-- <div class="links">
        <a href="#">Lupa Password?</a>
      </div> --}}
    </div>
  </div>
</form>

  @if(Session::has('error'))
      <script>
          document.addEventListener('DOMContentLoaded', function () {
            const Toast = Swal.mixin({
              toast: true,
              position: "top",
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
              }
            });
            Toast.fire({
              icon: 'error',
              title: '{{ Session::get('error') }}'
            });
          });
      </script>
  @endif
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
  <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
