<!doctype html>
<html lang="en">

<head>
<title>BAHAY KAINAN</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="assets/img/SG_Logo.png" type="image/png">
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    body {
            background-color: gray;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100%;
        }
    .drop-shadow-lg {
      box-shadow: 1px 4px 4px 0 rgba(0, 0, 0, 0.50);
    }

    .drop-shadow-xl {
      box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, 0.50);
    }
  </style>

</head>

<body class="flex justify-center items-center h-screen">
  <div class="container">
    <div class="flex justify-center items-center">
      <div class="w-full md:w-4/12">
        <div id="loginForm" class="card bg-neutral-100 p-5 floating-animation border border-black">
          <div class="card-body">
            

            <h2 class="card-title text-center font-bold text-2xl font-black mb-8 uppercase">los pollos hermanos</h2>
            <form action="api/login.php" method="POST">
              <div class="form-group w-full">
                <input type="text" class="form-control font-bold bg-neutral-300 w-full h-10 mb-5 px-2 autocomplete-none border border-black" id="uname"
                  name="uname" placeholder="Username">
              </div>
              <div class="form-group">
                <input type="password" class="form-control font-bold bg-neutral-300 w-full h-10 mb-5 px-2 autocomplete-none border border-black" id="pass"
                  name="pass" placeholder="Password">
              </div>
              <div class="flex justify-end mb-2 mt-10">
                <button type="submit"
                  class="btn text-white font-bold uppercase py-2 w-40 bg-blue-500">Login</button>
              </div>
              
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#uname').val(''); // Clear the input field's value on page load

      // Apply floating animation to the login form after page load
      setTimeout(function () {
        $('#loginForm').addClass('floating-animation');
      }, 1000);
    });
  </script>
</body>

</html>