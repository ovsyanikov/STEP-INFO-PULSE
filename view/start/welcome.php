<!doctype html>
<html>
<head>
	<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    
        <link rel="stylesheet" href="css/style.css">
        <link rel="shortcut icon" href="img/info-puls1.png">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
       	<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script> 
        <script type="text/javascript" src="js/script.js"></script> 
    
	<title>Info-Plus</title>

</head>
<body class="welc"> 
    <section class="fst-section">
	<div class="text">
            <img src="img/info-puls1.png" alt="" class="fst-logo"/><h1 class="in"> INFO PULSE </h1>
            
                <p>Your app needs to work where your users live, work and play – and that’s exactly where our testers are.
    SQA Solution puts your app through rigorous testing using professional </p><p>
    testers on real devices across locations and use cases that match your actual users.
    
    Your custom testing team and QA Project Manager are the winning combination to reduce overhead and maximize app quality.</p>
                <p>Your app needs to work where your users live, work and play – and that’s exactly where our testers are.
    SQA Solution puts your app through rigorous testing using professional </p><p>
    testers on real devices across locations and use cases that match your actual users.
    
    Your custom testing team and QA Project Manager are the winning combination to reduce overhead and maximize app quality.</p>
        </div>
        <div class="block" id="authentication">
            <form action="?ctrl=user&act=authorize" id="AuthoriseForm"  method= "POST">
                <input class="input" id="userLE" name="userLE" placeholder="Введите e-mail или логин пользователя">
                <input class="password input" id="userPS" name="userPS" placeholder="Пароль" type="password">
                <input id="Authorise" class="submit" value="Войти" type="button">
                <input type="checkbox" name="remember_me" class="checkbox"><span class="span">Запомнить /</span>
                <span class="span getnewpass"><a href="?ctrl=user&act=recovery"> Забыли пароль?</a></span>
            </form>	
            <div id="error_lp" class="invisible"></div>
        </div>
        <div class="block" id="registration">
            
            <h1 class="h1-form">Еще не зарегестрированы?</h1>
                
            <form action="?ctrl=user&act=mainRegister" id="registerForm" method= "POST">
                <input class="input" id="RLogin" name="RLogin" placeholder="Имя пользователя">
                <input class="input" name="REmail" placeholder="E-mail"  id="RMail">
                <input class="password input" name="Rpassword" placeholder="Пароль" type="password"  id="RPass">
                <input class="submit" id="register" value="Регистрация" type="button">
            </form>
            <div id="error" class="invisible"></div>
        </div> 
        
    </section>
    <footer>
        <h2 class="copyright">© Info-pulse 2015</h2>
    </footer>
</body>
</html>
