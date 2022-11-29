

update assets_clean
SET 
  report_type = trim(report_type);

update assets_clean
SET 
  asset_number = trim(asset_number);

update assets_clean
SET 
  asset_name = trim(asset_name);

update assets_clean
SET 
  asset_type = trim(asset_type);

update assets_clean
SET 
  asset_description = trim(asset_description);


 update assets_clean
SET 
  asset_received_date = trim(asset_received_date); 

update assets_clean
SET 
  asset_available_year = trim(asset_available_year);


update assets_clean
SET 
  asset_receive_type = trim(asset_receive_type);


update assets_clean
SET 
  asset_amount = trim(asset_amount);

update assets_clean
SET 
  asset_unit = replace(asset_unit,'1','');

update assets_clean
SET 
  asset_unit = trim(asset_unit);


update assets_clean
SET 
  asset_status = trim(asset_status);

update assets_clean
SET 
  asset_location = trim(asset_location);


-- ****** start mapping data ****** --
-- 1.1 update asset_type_code
update assets_clean c
left join asset_type t on (c.asset_type = t.asset_type_name)
set
	c.asset_type = t.asset_type_code
where t.asset_type_code is not null
;

-- 1.2 update asset_type_code
update assets_clean c
set
	c.asset_type = 10
where length(c.asset_type)>3
;


-- 2.1 update asset_receive_type 
update assets_clean c
left join asset_budget b on (c.asset_receive_type = b.asset_budget_name)
set
	c.asset_receive_type = b.asset_budget_code
where b.asset_budget_code is not null
;

-- 2.2 update asset_receive_type
update assets_clean c
set
	c.asset_receive_type = 2
where
	c.asset_receive_type = 'เงินประมาณ'
;

-- 2.3 update asset_receive_type
update assets_clean c
set
	c.asset_receive_type = 2
where
	c.asset_receive_type = 'งบประมาณ'
;

-- 2.4 update asset_receive_type
update assets_clean c
set
	c.asset_receive_type = 2
where
	c.asset_receive_type = 'งบประมมาณ'
;


-- 3.1 update asset_unit 
update assets_clean c
left join asset_unit u on (c.asset_unit = u.asset_unit_name)
set
	c.asset_unit = u.asset_unit_code
where u.asset_unit_code is not null
;


-- 4.1 update asset_status
update assets_clean c
left join asset_status s on (c.asset_status = s.asset_status_name)
set
	c.asset_status = s.asset_status_code
where s.asset_status_code is not null
;

-- 4.2 update asset_status
update assets_clean
SET 
  asset_status = 1
where
	asset_status = 'ใชได้' ;

-- 5.1 update asset_receive_date
UPDATE assets_clean SET asset_received_date = '2008-11-02' WHERE asset_received_date = '2 พ.ย.50' ; 
UPDATE assets_clean SET asset_received_date = '2017-10-31' WHERE asset_received_date = '22220' ; 
UPDATE assets_clean SET asset_received_date = '2019-12-11' WHERE asset_received_date = '11.12.2562' ; 
UPDATE assets_clean SET asset_received_date = '2014-07-31' WHERE asset_received_date = '21032' ; 
UPDATE assets_clean SET asset_received_date = '2012-02-23' WHERE asset_received_date = '23 ก.พ.55' ; 
UPDATE assets_clean SET asset_received_date = '2015-09-04' WHERE asset_received_date = '21432' ; 
UPDATE assets_clean SET asset_received_date = '2016-02-17' WHERE asset_received_date = '21598' ; 
UPDATE assets_clean SET asset_received_date = '2015-09-25' WHERE asset_received_date = '21453' ; 
UPDATE assets_clean SET asset_received_date = '2009-08-21' WHERE asset_received_date = '21 ส.ค.52' ; 
UPDATE assets_clean SET asset_received_date = '2014-07-16' WHERE asset_received_date = '21017' ; 
UPDATE assets_clean SET asset_received_date = '2018-02-09' WHERE asset_received_date = '22321' ; 
UPDATE assets_clean SET asset_received_date = '2009-08-17' WHERE asset_received_date = '17 ส.ค.52' ; 
UPDATE assets_clean SET asset_received_date = '2021-04-05' WHERE asset_received_date = '05.04.2563' ; 
UPDATE assets_clean SET asset_received_date = '2018-12-25' WHERE asset_received_date = '241786' ; 
UPDATE assets_clean SET asset_received_date = '2019-12-23' WHERE asset_received_date = '23.12.2562' ; 
UPDATE assets_clean SET asset_received_date = '2017-12-07' WHERE asset_received_date = '241403' ; 
UPDATE assets_clean SET asset_received_date = '2017-01-12' WHERE asset_received_date = '21927' ; 
UPDATE assets_clean SET asset_received_date = '2018-09-26' WHERE asset_received_date = '241696' ; 
UPDATE assets_clean SET asset_received_date = '2018-10-19' WHERE asset_received_date = '241719' ; 
UPDATE assets_clean SET asset_received_date = '2013-05-07' WHERE asset_received_date = '20582' ; 
UPDATE assets_clean SET asset_received_date = '2019-07-26' WHERE asset_received_date = '241999' ; 
UPDATE assets_clean SET asset_received_date = '2020-08-17' WHERE asset_received_date = '17.08.2563' ; 
UPDATE assets_clean SET asset_received_date = '2016-12-26' WHERE asset_received_date = '21910' ; 
UPDATE assets_clean SET asset_received_date = '2012-02-27' WHERE asset_received_date = '27 ก.พ.55' ; 
UPDATE assets_clean SET asset_received_date = '2020-06-11' WHERE asset_received_date = '242319' ; 
UPDATE assets_clean SET asset_received_date = '2018-08-20' WHERE asset_received_date = '241659' ; 
UPDATE assets_clean SET asset_received_date = '2011-05-25' WHERE asset_received_date = '25 พ.ค.54' ; 
UPDATE assets_clean SET asset_received_date = '2011-05-04' WHERE asset_received_date = '4 พ.ค.54' ; 
UPDATE assets_clean SET asset_received_date = '2018-09-17' WHERE asset_received_date = '241687' ; 
UPDATE assets_clean SET asset_received_date = '2012-11-26' WHERE asset_received_date = '26 พ.ย.55' ; 
UPDATE assets_clean SET asset_received_date = '2011-06-14' WHERE asset_received_date = '14 มิ.ย.54' ; 
UPDATE assets_clean SET asset_received_date = '2019-07-10' WHERE asset_received_date = '241983' ; 
UPDATE assets_clean SET asset_received_date = '2016-11-21' WHERE asset_received_date = '21875' ; 
UPDATE assets_clean SET asset_received_date = '2020-06-30' WHERE asset_received_date = '242338' ; 
UPDATE assets_clean SET asset_received_date = '2014-11-14' WHERE asset_received_date = '21138' ; 
UPDATE assets_clean SET asset_received_date = '2008-11-02' WHERE asset_received_date = '2 พ.ย.51' ; 
UPDATE assets_clean SET asset_received_date = '2018-04-02' WHERE asset_received_date = '22373' ; 
UPDATE assets_clean SET asset_received_date = '2019-05-15' WHERE asset_received_date = '241927' ; 
UPDATE assets_clean SET asset_received_date = '2011-02-09' WHERE asset_received_date = '9 ก.พ.54' ; 
UPDATE assets_clean SET asset_received_date = '2012-01-23' WHERE asset_received_date = '23 ม.ค.55' ; 
UPDATE assets_clean SET asset_received_date = '2015-03-25' WHERE asset_received_date = '25 มีค.58' ; 
UPDATE assets_clean SET asset_received_date = '2020-08-06' WHERE asset_received_date = '06.08.2563' ; 
UPDATE assets_clean SET asset_received_date = '2019-02-11' WHERE asset_received_date = '241834' ; 
UPDATE assets_clean SET asset_received_date = '2020-04-29' WHERE asset_received_date = '29.04.2563' ; 
UPDATE assets_clean SET asset_received_date = '2012-02-05' WHERE asset_received_date = '5 ก.พ.55' ; 
UPDATE assets_clean SET asset_received_date = '2011-07-04' WHERE asset_received_date = '4 ก.ค.54' ; 
UPDATE assets_clean SET asset_received_date = '2012-02-26' WHERE asset_received_date = '26 ก.พ.55' ; 
UPDATE assets_clean SET asset_received_date = '2017-07-21' WHERE asset_received_date = '21 กค.60' ; 
UPDATE assets_clean SET asset_received_date = '2012-12-03' WHERE asset_received_date = '3 ธ.ค.55' ; 
UPDATE assets_clean SET asset_received_date = '2015-11-20' WHERE asset_received_date = '20 พย.58' ; 
UPDATE assets_clean SET asset_received_date = '2009-08-22' WHERE asset_received_date = '22 ส.ค.52' ; 
UPDATE assets_clean SET asset_received_date = '2019-12-16' WHERE asset_received_date = '16.12.2562' ; 
UPDATE assets_clean SET asset_received_date = '2016-03-29' WHERE asset_received_date = '29 มีค.59' ; 
UPDATE assets_clean SET asset_received_date = '2010-05-25' WHERE asset_received_date = '25 พ.ค.53' ; 
UPDATE assets_clean SET asset_received_date = '2019-12-20' WHERE asset_received_date = '20.12.2562' ; 
UPDATE assets_clean SET asset_received_date = '2020-04-27' WHERE asset_received_date = '27.04.2563' ; 
UPDATE assets_clean SET asset_received_date = '2011-03-12' WHERE asset_received_date = '12 มี.ค.54' ; 
UPDATE assets_clean SET asset_received_date = '2012-02-24' WHERE asset_received_date = '24 ก.พ.55' ; 
UPDATE assets_clean SET asset_received_date = '2020-09-22' WHERE asset_received_date = '22.09.02563' ; 
UPDATE assets_clean SET asset_received_date = '2020-08-17' WHERE asset_received_date = '17 ส.ค.63' ; 
UPDATE assets_clean SET asset_received_date = '2011-08-02' WHERE asset_received_date = '2 ส.ค.54' ; 
UPDATE assets_clean SET asset_received_date = '2020-11-25' WHERE asset_received_date = '25/11/2563' ;  
UPDATE assets_clean SET asset_received_date = '2012-02-25' WHERE asset_received_date = '25 ก.พ.55' ; 
UPDATE assets_clean SET asset_received_date = '2020-06-18' WHERE asset_received_date = '18.06.2563' ; 
UPDATE assets_clean SET asset_received_date = '2020-04-23' WHERE asset_received_date = '23.04.2563' ; 



-- no all data just some and there is the test has not update yet

























