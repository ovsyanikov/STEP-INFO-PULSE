<!doctype html>
<html>
<head>
	<meta charset="utf-8">
    
        <link rel="stylesheet" href="css/style.css">
        <link rel="shortcut icon" href="img/info-puls1.png">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
       	<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script> 
        <script type="text/javascript" src="js/script.js"></script> 
    
	<title>Info-Plus</title>

</head>
<body class="welc"> 
    <section class="reg-section">
	<div class="head-reg">
            <img src="img/info-puls1.png" alt="" class="reg-logo"/>
            <h1 class="in"> INFO PULSE </h1>
            <h2 class="h2-reg h2">Регистрация</h2>   
        </div>
        <div class="reg-block block" id="authentication">
            <form action="?ctrl=user&act=newuser" id="registerForm" method= "POST">
                <h2 class="h2-reg-form">Выберите логин</h2>
                <input class="input" id="newUserLogin" name="userLE" placeholder="Логин" value="<?php echo"{$this->view->NewUser->getLogin()}"; ?>">
                <h2 class="h2-reg-form">Введите Ваш e-mail</h2>
                <input class="input" id="newMail" name="REmail" placeholder="E-mail" value="<?php echo"{$this->view->NewUser->getEmail()}"; ?>" >
                <h2 class="h2-reg-form">Создать пароль</h2>
                <input class="input" id="userPS" name="userPS" placeholder="Пароль" type="password" value="<?php echo"{$this->view->NewUser->getPassword()}"; ?>">
                <input class="input" id="userPS2" name="userPS2" placeholder="Повторите ввод" type="password">
                <h2 class="h2-reg-form">Ваши имя и фамилия</h2>
                <input class="input" id="NewFirstName" name="NewFirstName" placeholder="Имя">
                <input class="input" id="NewLastName" name="NewLastName" placeholder="Фамилия">
                <input class="reg-button submit" id="registerNewUser" value="Зарегистрироваться" type="button">
                
            </form>
            <div id="error" class="invisible"></div>
        </div> 
        
    </section>
    <footer>
        <h2 class="copyright">© Info-pulse 2015</h2>
    </footer>
</body>
</html>
