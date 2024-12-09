<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="./css/index.min.css" />
    <style type="text/css">
      .login-form {
        position: relative;
      }
      .error {
        position: absolute;
        margin-top: -35px;
        color: #da3a8d;
        display: none;
      }
    </style>
  </head>
  <body>
    <header class="header">
      <div class="container">
        <a href="#" class="header-logo">
          <img src="./img/header/logo.svg" alt="header logo" />
        </a>

        <a href="#" class="header-logout">
          Déconnexion
          <img src="./img/header/logout.svg" alt="header logout" />
        </a>
      </div>
    </header>

    <main class="login">
      <section>
        <form class="login-form">
          <h1 class="login-form__title">Connexion</h1>

          <img
            class="login-form__img"
            src="./img/login/trait.svg"
            alt="trait"
          />
          <input
            type="text"
            class="login-form__input login-input"
            placeholder="Votre identifiant"
            required />
          <input
            type="password"
            class="login-form__input password-input"
            placeholder="Votre mot de passe"
            required />
          <div class="error">Identifiant ou mot de passe incorrect !</div>
          <button type="submit" class="login-form__btn">Se connecter</button>
        </form>
      </section>
    </main>

    <script type="text/javascript" src="./js/app.js"></script>
    <script type="text/javascript">
      const form = document.querySelector('.login-form'),
            login = document.querySelector('.login-input'),
            password = document.querySelector('.password-input'),
            error = document.querySelector('.error');
      form.addEventListener('submit', (event) => {
        event.preventDefault();
        error.style.display = 'none';
        const request = new XMLHttpRequest();
        const url = '../manager/d26386b04e.php';
        const params = 'event=login_order&login=' + login.value + '&password=' + password.value;
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.addEventListener("readystatechange", () => {
          if(request.readyState === 4 && request.status === 200) {
            if (request.responseText == 'done') {
              window.location.href = './';
            } else {
              error.style.display = 'block';
            }
          }
        });
        request.send(params);
      });
    </script>
  </body>
</html>
