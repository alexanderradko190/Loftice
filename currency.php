<?php
/**
 * Class Bills
 * @package App
 *
 * @property int $id
 * @property double $cost
 * @property Carbon $date
 * @property int $currency_key
 * 
 * @example ['id'=>1, 'cost'=>200.50, 'date'=>'2024-01-01', 'currency_key'=>'USD'];
 */
class Bills extends Model
{
    protected $table = "bills";

    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_key', 'key');
    }
}

/**
 * Class Currency
 * @package App
 *
 * @property string $key
 *
 * @example [key'=>'RUB'];
 */
class Currency extends Model
{
    protected $table = "currencies";

    const RUB_KEY = 'RUB';
}

/**
 * Class ExchangeRate
 * @package App
 *
 * @property int $id
 * @property int $currency_key
 * @property double $rate
 * @property Carbon $date
 *
 * @example ['id'=>3, 'currency_key'=>'EUR', 'rate'=>99.19 ,'date'=>'2024-01-01'];
 */
class ExchangeRate extends Model
{
    protected $table = "exchange_rates";

    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_key', 'key');
    }
}

/*
 * Задание 6 - вернуть список счетов за указанный диапазон дат (с $date_from по $date_to)
 * с конвертацией валюты счета в валюту $currency_key по курсу даты счета.
 * Курс валют есть за каждый день и для каждой валюты счетов. 
 * Курс валют хранится только в рублях.
 * Изменение классов моделей не допускается.
 * При реализации постараться учесть оптимизацию, т.к. за отрезок дат могут существовать несколько миллионов счетов 
*/

function getData(Carbon $date_from, Carbon $date_to, string $currency_key): Collection
{
    $exchangeRates = ExchangeRate::whereBetween('date', [$date_from, $date_to])
                                 ->where('currency_key', $currency_key)
                                 ->get()
                                 ->keyBy('date');

    $bills = Bills::with('currency')
                  ->whereBetween('date', [$date_from, $date_to])
                  ->get();

    foreach ($bills as $bill) {
        $bill->cost = $bill->cost * $exchangeRates[$bill->date]->rate;
        $bill->currency_key = $currency_key;
    }

    return $bills;
}
