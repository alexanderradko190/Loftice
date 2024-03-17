/* 
Задание 5
Дано 3 таблицы: 
first_table: id,name
second_table: id,name,first_id 
third_table: id,name,second_id,cost
Написать один SQL запрос, в результате которого будет таблица вида: 
first_table.name,
total_cost. Где total_cost - сумма всех third_table.cost связанных с этой записью из first_table.
*/
CREATE TABLE first_table (
id INT(6) AUTO_INCREMENT PRIMARY KEY, 
name VARCHAR(80) NOT NULL
);
CREATE TABLE second_table (
id INT(6) AUTO_INCREMENT PRIMARY KEY, 
name VARCHAR(80) NOT NULL,
first_id INT(6) NOT NULL
);
CREATE TABLE third_table (
id INT(6) AUTO_INCREMENT PRIMARY KEY, 
name VARCHAR(80) NOT NULL,
second_id INT(6) NOT NULL,
cost DOUBLE(11,2) NOT NULL
);

select fft.name, SUM(tt.cost) as total_cost
from first_table ft
left join second_table st on ft.id = st.first_id
left join third_table tt on st.id = tt.second_id
group by ft.name