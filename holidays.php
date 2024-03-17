<?php
$calendar = ['2020-01-01','2020-01-02','2020-04-23'];
calendar($calendar,2020);
echo implode(' ',$calendar);

//Задание 4 - исправить код до работающего варианта. Допускается любое редактирование\замена указанного ниже кода.
//До 22 строки код рабочий, но его тоже можно редактировать.

/**
 * Функция догрузки выходных дней.
 * Должна изменять исходный массив holidays, догружая даты ТОЛЬКО выходных дней в формате Y-m-d без дубликатов по датам.
 * http://xmlcalendar.ru/data/ru/2020/calendar.xml - реальная ссылка, возвращает xml с производственным календарем.
 * @param array $holidays
 * @param integer $n_year
 */

function calendar(&$holidays, $n_year){
    $c = file_get_contents('http://xmlcalendar.ru/data/ru/'.$n_year.'/calendar.xml');
    $xml = simplexml_load_string($c);

    foreach ($xml->days[0] as $day) {
        $t_arr = [];
        foreach ($day->attributes() as $attr=>$val)
            $t_arr[$attr] = (string)$val;

        $p_day = $n_year . '-' . str_replace('.', '-', $t_arr['d']);
        $h = new (Holidays::class);
        $h->day = $p_day;
        $h->type = $t_arr['t']; //1 - выходной день, 2 - короткий день, 3 - рабочий день
        $h->save(true);
        if ($h->type == 1)
            $holidays[] = $h->day;
    }
    return array_unique($holidays);
}

class Holidays{
    public $day;
    public $type;

    /**
     * Функция - имитация сохранения
     * @param bool $for_save
     */
    
    public function save($for_save = false){
        $this->type = $for_save ? $this->type : rand(1,3);
    }
}