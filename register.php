<?php  
  include_once 'config.php';
  if($user->isLoggedIn()) {
    header('location:index.php');
  }

  if(isset($_POST['kirim'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if($user->register($nama, $email, $password)) {
      $success = true;
    } else {
      $error = $user->getLastError();
    }
  }
?>

<!DOCTYPE html>  

 <html>  

   <head> 

     <meta charset="utf-8"> 

     <title>Register</title> 

     <link rel="stylesheet" href="style.css" media="screen" title="no title" charset="utf-8"> 

   </head> 

   <body> 

     <div class="login-page"> 

      <div class="form"> 

        <form class="register-form" method="post"> 

        <?php if (isset($error)): ?> 

          <div class="error"> 

            <?php echo $error ?> 

          </div> 

        <?php endif; ?> 

        <?php if (isset($success)): ?> 

          <div class="success"> 

            Berhasil mendaftar. Silakan <a href="login.php">login</a>. 

          </div> 

        <?php endif; ?> 

         <input type="text" name="nama" placeholder="nama" required/> 

         <input type="email" name="email" placeholder="email address" required/> 

         <input type="password" name="password" placeholder="password" required/> 

         <button type="submit" name="kirim">create</button> 

         <p class="message">Already registered? <a href="login.php">Sign In</a></p> 

        </form> 

      </div> 

     </div> 

   </body> 

 </html> 