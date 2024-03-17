<?php

//Задание 3 - выводить в переменную $result список всех последних email через запятую, успешно отправленных из формы по кнопке send.
//Т. е. если форма была отправлена 5 раз подряд - вывести эти введенные 5 email.
//Хранение данных между отправками по своему усмотрению.
//Не учитывать переход на другие страницы, закрытие браузера и т. д.

$result = explode(',', file_get_contents('./email.txt'));
if ($_POST['email']) {
    array_push($result, $_POST['email']);
    file_put_contents('./email.txt', implode(',', $result));
}

?>
<form id="email_form" method="POST" action="">
    <label>Email</label>
    <input id="email" name="email" type="text">
    <br>
    <label>Key</label>
    <input id="key" name="key" type="text">
    <br>
    <input type="submit" id="ajax" name="ajax" value="Получить">
    <input type="submit" id="send" name="send" value="Отправить">
</form>
<label>Результат:</label>
<br>
<label><?= (count($result) > 0 ? implode(',', $result) : '- - -');?></label>
<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<script>
    $(function () {

        //Задание 1 - в поле key запретить внесение любых значений, кроме 0,1,2,-1.
        //По кнопке send провести валидацию поля email на соответствие формату email (test@example.com), 
		//не отправлять форму и выдавать сообщение об ошибке, если не соответствует требуемому формату.
        //Это задание выполнять только средствами JS c jQuery.

        $('#key').on('input', (e) => {
            let key_val = $(e.target).val();
            if (!['0','1','2','-', '-1'].includes(key_val)) {
                $(e.target).val('');
            }
        });

        $('#send').on('click', e => {
            e.preventDefault();
            email_regex = /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/;
            if(!email_regex.test($('#email').val())) {
                alert(`Email ${$('#email').val()} is not valid`);
                return;
            }
            $('#email_form').submit();
        });

        //Задание 2 - по кнопке ajax AJAX запросом получить случайное значение из подмассива $arr с ключом,
        //основанном на введенном значении в поле key ajax.php. Если введено -1 - возвращать элемент случайного подмассива.
        //Пример - в key введено 2, нужно вернуть $arr[2][$rnd], где $rnd - случайное значение в пределах подмассива.
		//Отображать возвращенное значение любым удобным способом.
        //Допускается любое редактирование файла ajax.php, кроме массива $arr.

        $('#ajax').on('click', (e) => {
            e.preventDefault();
            if ($('#key').val()) {
                fetch('/ajax.php?' + new URLSearchParams({
                    key: $('#key').val(),
                })).then(data => data.json()).then(res => console.log(res.result))
            }
        });
    });
</script>
<?php

?>